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

namespace Valkyrja\Console;

use Valkyrja\Config\Config;
use Valkyrja\Console\Config as ConsoleConfig;
use Valkyrja\Console\Config\Cache;
use Valkyrja\Console\Model\Contract\Command;
use Valkyrja\Support\Cacheable\Cacheable;

use function base64_decode;
use function base64_encode;
use function is_file;
use function serialize;
use function unserialize;

/**
 * Class CacheableConsole.
 *
 * @author Melech Mizrachi
 */
class CacheableConsole extends Console
{
    /**
     * @use Cacheable<ConsoleConfig, array<string, mixed>, Cache>
     */
    use Cacheable;

    /**
     * @inheritDoc
     */
    public function getCacheable(): Config
    {
        $this->setup(true, false);

        $config                = new Cache();
        $config->commands      = base64_encode(serialize(self::$commands));
        $config->paths         = self::$paths;
        $config->namedCommands = self::$namedCommands;
        $config->provided      = $this->deferred;

        return $config;
    }

    /**
     * @inheritDoc
     *
     * @return ConsoleConfig|array<string, mixed>
     */
    protected function getConfig(): Config|array
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     *
     * @param ConsoleConfig|array<string, mixed> $config
     */
    protected function beforeSetup(Config|array $config): void
    {
    }

    /**
     * @inheritDoc
     *
     * @param ConsoleConfig|array<string, mixed> $config
     */
    protected function setupFromCache(Config|array $config): void
    {
        $cache = $config['cache'] ?? null;

        if ($cache === null) {
            $cache         = [];
            $cacheFilePath = $config['cacheFilePath'];

            if (is_file($cacheFilePath)) {
                $cache = require $cacheFilePath;
            }
        }

        $decodedCommands = base64_decode($cache['commands'], true);

        if ($decodedCommands !== false) {
            /** @var Command[] $commands */
            $commands = unserialize(
                $decodedCommands,
                [
                    'allowed_classes' => [
                        Command::class,
                    ],
                ]
            );

            self::$commands = $commands;
        }

        self::$paths         = $cache['paths'];
        self::$namedCommands = $cache['namedCommands'];
        $this->deferred      = $cache['provided'];
    }

    /**
     * @inheritDoc
     *
     * @param ConsoleConfig|array<string, mixed> $config
     */
    protected function setupNotCached(Config|array $config): void
    {
        self::$paths         = [];
        self::$commands      = [];
        self::$namedCommands = [];

        // Setup command providers
        $this->setupCommandProviders($config);
    }

    /**
     * @inheritDoc
     *
     * @param ConsoleConfig|array<string, mixed> $config
     */
    protected function setupAttributes(Config|array $config): void
    {
    }

    /**
     * Setup command providers.
     *
     * @param ConsoleConfig|array<string, mixed> $config
     *
     * @return void
     */
    protected function setupCommandProviders(Config|array $config): void
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
     * @inheritDoc
     *
     * @param ConsoleConfig|array<string, mixed> $config
     */
    protected function afterSetup(Config|array $config): void
    {
    }
}
