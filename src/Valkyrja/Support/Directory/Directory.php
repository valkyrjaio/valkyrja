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

namespace Valkyrja\Support\Directory;

/**
 * Class Directory.
 *
 * @author Melech Mizrachi
 */
class Directory
{
    /**
     * Directory separator.
     *
     * @var string
     */
    public const string DIRECTORY_SEPARATOR = '/';

    /**
     * Base directory path.
     *
     * @var string
     */
    public static string $BASE_PATH = '';

    /**
     * Various paths for the application.
     *
     * @var string
     */
    public static string $APP_PATH               = 'app';
    public static string $CONFIG_PATH            = 'config';
    public static string $ENV_PATH               = 'env';
    public static string $PUBLIC_PATH            = 'public';
    public static string $RESOURCES_PATH         = 'resources';
    public static string $STORAGE_PATH           = 'storage';
    public static string $FRAMEWORK_STORAGE_PATH = 'framework';
    public static string $LOGS_STORAGE_PATH      = 'logs';
    public static string $CACHE_PATH             = 'cache';
    public static string $TESTS_PATH             = 'tests';
    public static string $VENDOR_PATH            = 'vendor';

    /**
     * Get the app directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function appPath(string|null $path = null): string
    {
        return static::basePath(static::$APP_PATH . static::path($path));
    }

    /**
     * Get the base directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function basePath(string|null $path = null): string
    {
        return static::$BASE_PATH . static::path($path);
    }

    /**
     * Construct a path with the directory separator prepended.
     *
     * @param string|null $path The path
     *
     * @return string
     */
    public static function path(string|null $path = null): string
    {
        return $path !== null && $path !== '' && $path[0] !== static::DIRECTORY_SEPARATOR
            ? static::DIRECTORY_SEPARATOR . $path
            : $path ?? '';
    }

    /**
     * Get the config directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function configPath(string|null $path = null): string
    {
        return static::basePath(static::$CONFIG_PATH . static::path($path));
    }

    /**
     * Get the env directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function envPath(string|null $path = null): string
    {
        return static::basePath(static::$ENV_PATH . static::path($path));
    }

    /**
     * Get the public directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function publicPath(string|null $path = null): string
    {
        return static::basePath(static::$PUBLIC_PATH . static::path($path));
    }

    /**
     * Get the resources directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function resourcesPath(string|null $path = null): string
    {
        return static::basePath(static::$RESOURCES_PATH . static::path($path));
    }

    /**
     * Get the storage directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function storagePath(string|null $path = null): string
    {
        return static::basePath(static::$STORAGE_PATH . static::path($path));
    }

    /**
     * Get the framework storage directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function frameworkStoragePath(string|null $path = null): string
    {
        return static::storagePath(static::$FRAMEWORK_STORAGE_PATH . static::path($path));
    }

    /**
     * Get the logs storage directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function logsStoragePath(string|null $path = null): string
    {
        return static::storagePath(static::$LOGS_STORAGE_PATH . static::path($path));
    }

    /**
     * Get the cache directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function cachePath(string|null $path = null): string
    {
        return static::frameworkStoragePath(static::$CACHE_PATH . static::path($path));
    }

    /**
     * Get the tests directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function testsPath(string|null $path = null): string
    {
        return static::basePath(static::$TESTS_PATH . static::path($path));
    }

    /**
     * Get the vendor directory for the application.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    public static function vendorPath(string|null $path = null): string
    {
        return static::basePath(static::$VENDOR_PATH . static::path($path));
    }
}
