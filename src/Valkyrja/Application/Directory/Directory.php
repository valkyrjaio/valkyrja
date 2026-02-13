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

namespace Valkyrja\Application\Directory;

class Directory
{
    /** @var non-empty-string */
    public static string $basePath = __DIR__;
    /** @var non-empty-string */
    public static string $appPath = 'app';
    /** @var non-empty-string */
    public static string $dataPath = 'data';
    /** @var non-empty-string */
    public static string $envPath = 'env';
    /** @var non-empty-string */
    public static string $publicPath = 'public';
    /** @var non-empty-string */
    public static string $resourcesPath = 'resources';
    /** @var non-empty-string */
    public static string $storagePath = 'storage';
    /** @var non-empty-string */
    public static string $frameworkStoragePath = 'framework';
    /** @var non-empty-string */
    public static string $cacheStoragePath = 'cache';
    /** @var non-empty-string */
    public static string $logsStoragePath = 'logs';
    /** @var non-empty-string */
    public static string $testsPath = 'tests';
    /** @var non-empty-string */
    public static string $vendorPath = 'vendor';

    /**
     * Get the app directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function appPath(string|null $path = null): string
    {
        return static::basePath(static::$appPath . static::path($path));
    }

    /**
     * Get the base directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function basePath(string|null $path = null): string
    {
        return static::$basePath . static::path($path);
    }

    /**
     * Construct a path with the directory separator prepended.
     *
     * @param string|null $path The path
     */
    public static function path(string|null $path = null): string
    {
        return $path !== null && $path !== '' && $path[0] !== '/'
            ? '/' . $path
            : $path ?? '';
    }

    /**
     * Get the data directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function dataPath(string|null $path = null): string
    {
        return static::basePath(static::$dataPath . static::path($path));
    }

    /**
     * Get the env directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function envPath(string|null $path = null): string
    {
        return static::basePath(static::$envPath . static::path($path));
    }

    /**
     * Get the public directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function publicPath(string|null $path = null): string
    {
        return static::basePath(static::$publicPath . static::path($path));
    }

    /**
     * Get the resources directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function resourcesPath(string|null $path = null): string
    {
        return static::basePath(static::$resourcesPath . static::path($path));
    }

    /**
     * Get the storage directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function storagePath(string|null $path = null): string
    {
        return static::basePath(static::$storagePath . static::path($path));
    }

    /**
     * Get the framework storage directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function frameworkStoragePath(string|null $path = null): string
    {
        return static::storagePath(static::$frameworkStoragePath . static::path($path));
    }

    /**
     * Get the logs storage directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function logsStoragePath(string|null $path = null): string
    {
        return static::storagePath(static::$logsStoragePath . static::path($path));
    }

    /**
     * Get the framework storage cache directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function frameworkStorageCachePath(string|null $path = null): string
    {
        return static::frameworkStoragePath(static::$cacheStoragePath . static::path($path));
    }

    /**
     * Get the tests directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function testsPath(string|null $path = null): string
    {
        return static::basePath(static::$testsPath . static::path($path));
    }

    /**
     * Get the vendor directory for the application.
     *
     * @param non-empty-string|null $path [optional] The path to append
     *
     * @return non-empty-string
     */
    public static function vendorPath(string|null $path = null): string
    {
        return static::basePath(static::$vendorPath . static::path($path));
    }
}
