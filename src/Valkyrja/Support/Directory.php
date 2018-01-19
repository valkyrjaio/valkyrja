<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support;

/**
 * class Directory.
 *
 * @author Melech Mizrachi
 */
class Directory
{
    /**
     * Directory separator.
     *
     * @constant string
     */
    public const DIRECTORY_SEPARATOR = DIRECTORY_SEPARATOR;

    /**
     * Base directory path.
     *
     * @constant string
     */
    public static $BASE_PATH;

    /**
     * Various paths for the application.
     *
     * @var string
     */
    public static $APP_PATH       = 'app';
    public static $BOOTSTRAP_PATH = 'bootstrap';
    public static $CONFIG_PATH    = 'config';
    public static $PUBLIC_PATH    = 'public';
    public static $RESOURCES_PATH = 'resources';
    public static $ROUTES_PATH    = 'routes';
    public static $STORAGE_PATH   = 'storage';
    public static $CACHE_PATH     = 'framework/cache';
    public static $TESTS_PATH     = 'tests';
    public static $VENDOR_PATH    = 'vendor';

    /**
     * Get the base directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function basePath(string $path = null): string
    {
        return static::$BASE_PATH . static::path($path);
    }

    /**
     * Get the app directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function appPath(string $path = null): string
    {
        return static::basePath(static::$APP_PATH . static::path($path));
    }

    /**
     * Get the bootstrap directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function bootstrapPath(string $path = null): string
    {
        return static::basePath(static::$BOOTSTRAP_PATH . static::path($path));
    }

    /**
     * Get the config directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function configPath(string $path = null): string
    {
        return static::basePath(static::$CONFIG_PATH . static::path($path));
    }

    /**
     * Get the public directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function publicPath(string $path = null): string
    {
        return static::basePath(static::$PUBLIC_PATH . static::path($path));
    }

    /**
     * Get the resources directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function resourcesPath(string $path = null): string
    {
        return static::basePath(static::$RESOURCES_PATH . static::path($path));
    }

    /**
     * Get the routes directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function routesPath(string $path = null): string
    {
        return static::basePath(static::$ROUTES_PATH . static::path($path));
    }

    /**
     * Get the storage directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function storagePath(string $path = null): string
    {
        return static::basePath(static::$STORAGE_PATH . static::path($path));
    }

    /**
     * Get the cache directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function cachePath(string $path = null): string
    {
        return static::storagePath(static::$CACHE_PATH . static::path($path));
    }

    /**
     * Get the tests directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function testsPath(string $path = null): string
    {
        return static::basePath(static::$TESTS_PATH . static::path($path));
    }

    /**
     * Get the vendor directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function vendorPath(string $path = null): string
    {
        return static::basePath(static::$VENDOR_PATH . static::path($path));
    }

    /**
     * Construct a path with the directory separator prepended.
     *
     * @param string $path The path
     *
     * @return string
     */
    public static function path(string $path = null): ? string
    {
        return $path && $path[0] !== static::DIRECTORY_SEPARATOR
            ? static::DIRECTORY_SEPARATOR . $path
            : $path;
    }
}
