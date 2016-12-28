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
 * class Directory
 *
 * @package Valkyrja
 *
 * @author  Melech Mizrachi
 */
class Directory
{
    /**
     * Directory separator.
     *
     * @constant string
     */
    public const DIRECTORY_SEPARATOR = '/';

    /**
     * Base directory path.
     *
     * @constant string
     */
    public static $BASE_PATH;

    /**
     * Get the base directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function basePath(string $path = null): string
    {
        return static::$BASE_PATH . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path);
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
        return static::basePath(
            'app' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
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
        return static::basePath(
            'config' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the framework directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function frameworkPath(string $path = null): string
    {
        return static::basePath(
            'framework' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
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
        return static::basePath(
            'public' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
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
        return static::basePath(
            'resources' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
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
        return static::basePath(
            'routes' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
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
        return static::basePath(
            'storage' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
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
        return static::basePath(
            'tests' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
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
        return static::basePath(
            'vendor' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }
}
