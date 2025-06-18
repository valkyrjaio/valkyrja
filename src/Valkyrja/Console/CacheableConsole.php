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

use Valkyrja\Console\Config\Cache;
use Valkyrja\Console\Model\Contract\Command;

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
    /**
     * Has setup already completed? Used to avoid duplicate setup.
     *
     * @var bool
     */
    protected bool $setup = false;

    /**
     * Setup the collection.
     */
    public function setup(bool $force = false, bool $useCache = true): void
    {
        // If route's have already been setup, no need to do it again
        if ($this->setup && ! $force) {
            return;
        }

        $this->setup = true;

        $cache = $this->config->cache;

        // If the application should use the routes cache file
        if ($useCache && $cache !== null) {
            $this->setupFromCache($cache);

            // Then return out of setup
            return;
        }

        $this->setupNotCached();
    }

    /**
     * Get a cacheable representation of the collection.
     */
    public function getCacheable(): Cache
    {
        $this->setup(true, false);

        $config = new Cache();

        $config->commands      = base64_encode(serialize($this->commands));
        $config->paths         = $this->paths;
        $config->namedCommands = $this->namedCommands;
        $config->provided      = $this->deferred;

        return $config;
    }

    /**
     * Setup from cache.
     */
    protected function setupFromCache(Cache $cache): void
    {
        $decodedCommands = base64_decode($cache->commands, true);

        if ($decodedCommands !== false) {
            /** @var array<string, Command> $commands */
            $commands = unserialize(
                $decodedCommands,
                [
                    'allowed_classes' => [
                        Command::class,
                    ],
                ]
            );

            $this->commands = $commands;
        }

        $this->paths         = $cache->paths;
        $this->namedCommands = $cache->namedCommands;
        $this->deferred      = $cache->provided;
    }

    /**
     * Setup not cached.
     */
    protected function setupNotCached(): void
    {
        // Setup command providers
        $this->setupCommandProviders();
        $this->setupAttributedCommands();
    }

    protected function setupAttributedCommands(): void
    {
    }

    /**
     * Setup command providers.
     */
    protected function setupCommandProviders(): void
    {
        // Iterate through all the providers
        foreach ($this->config->providers as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->debug) {
            return;
        }

        // Iterate through all the providers
        foreach ($this->config->devProviders as $provider) {
            $this->register($provider);
        }
    }
}
