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
        // The cacheable config
        $config = $this->config;

        $configUseCache = $config->shouldUseCache;

        // If the application should use the routes cache file
        if ($useCache && $configUseCache) {
            $this->setupFromCache();

            // Then return out of setup
            return;
        }

        $this->setupNotCached();
        $this->setupAttributedCommands();
        $this->requireFilePath();
    }

    /**
     * Get a cacheable representation of the collection.
     */
    public function getCacheable(): Cache
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
     */
    protected function setupFromCache(): void
    {
        $cache = $this->config->cache ?? null;

        if ($cache === null) {
            $cache         = [];
            $cacheFilePath = $this->config->cacheFilePath;

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
     */
    protected function setupNotCached(): void
    {
        self::$paths         = [];
        self::$commands      = [];
        self::$namedCommands = [];

        // Setup command providers
        $this->setupCommandProviders();
    }

    /**
     */
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

    /**
     * Require the file path specified in the config.
     */
    protected function requireFilePath(): void
    {
        $filePath = $this->config->filePath;

        if (is_file($filePath)) {
            $console = $this;

            require $filePath;
        }
    }
}
