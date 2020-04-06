<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Dispatchers;

use ReflectionException;
use Valkyrja\Config\Config;
use Valkyrja\Console\Annotation\CommandAnnotator;
use Valkyrja\Console\Command;
use Valkyrja\Console\Config\Cache;
use Valkyrja\Console\Config\Config as ConsoleConfig;
use Valkyrja\Console\Console as Contract;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Path\PathParser;
use Valkyrja\Support\Cacheables\Cacheable;

use function base64_decode;
use function base64_encode;
use function serialize;
use function unserialize;

/**
 * Class CacheableConsole.
 *
 * @author Melech Mizrachi
 */
class CacheableConsole extends Console
{
    use Cacheable;

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Contract::class,
            $console = new static(
                $container,
                $container->getSingleton(Dispatcher::class),
                $container->getSingleton(Events::class),
                $container->getSingleton(PathParser::class),
                (array) $config['console'],
                $config['app']['debug'],
            )
        );

        $console->setup();
    }

    /**
     * Get a cacheable representation of the commands.
     *
     * @return ConsoleConfig|object
     */
    public function getCacheable(): object
    {
        $this->setup(true, false);

        $config                = new Cache();
        $config->commands      = base64_encode(serialize(self::$commands));
        $config->paths         = self::$paths;
        $config->namedCommands = self::$namedCommands;
        $config->provided      = self::$provided;

        return $config;
    }

    /**
     * Get the config.
     *
     * @return ConsoleConfig|array
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * Before setup.
     *
     * @param Config|array $config
     *
     * @return void
     */
    protected function beforeSetup($config): void
    {
    }

    /**
     * Setup the console from cache.
     *
     * @param ConsoleConfig|array $config
     *
     * @return void
     */
    protected function setupFromCache(array $config): void
    {
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        self::$commands      = unserialize(
            base64_decode($cache['commands'], true),
            [
                'allowed_classes' => [
                    Command::class,
                ],
            ]
        );
        self::$paths         = $cache['paths'];
        self::$namedCommands = $cache['namedCommands'];
        self::$provided      = $cache['provided'];
    }

    /**
     * Set not cached.
     *
     * @param ConsoleConfig|array $config
     *
     * @return void
     */
    protected function setupNotCached($config): void
    {
        self::$paths         = [];
        self::$commands      = [];
        self::$namedCommands = [];

        // Setup command providers
        $this->setupCommandProviders($config);
    }

    /**
     * Setup annotations.
     *
     * @param ConsoleConfig|array $config
     *
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setupAnnotations($config): void
    {
        /** @var CommandAnnotator $commandAnnotations */
        $commandAnnotations = $this->container->getSingleton(CommandAnnotator::class);

        // Get all the annotated commands from the list of handlers
        // Iterate through the commands
        foreach ($commandAnnotations->getCommands(...$config['handlers']) as $command) {
            // Set the service
            $this->addCommand($command);
        }
    }

    /**
     * Setup command providers.
     *
     * @param ConsoleConfig|array $config
     *
     * @return void
     */
    protected function setupCommandProviders($config): void
    {
        // Iterate through all the providers
        foreach ($config['providers'] as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->debug) {
            return;
        }

        // Iterate through all the providers
        foreach ($config['devProviders'] as $provider) {
            $this->register($provider);
        }
    }

    /**
     * After setup.
     *
     * @param ConsoleConfig|array $config
     *
     * @return void
     */
    protected function afterSetup($config): void
    {
    }
}
