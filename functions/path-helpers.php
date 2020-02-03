<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Support\Directory;

if (! function_exists('basePath')) {
    /**
     * Helper function to get base path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function basePath(string $path = null): string
    {
        return Directory::basePath($path);
    }
}

if (! function_exists('appPath')) {
    /**
     * Helper function to get app path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function appPath(string $path = null): string
    {
        return Directory::appPath($path);
    }
}

if (! function_exists('envPath')) {
    /**
     * Helper function to get env path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function envPath(string $path = null): string
    {
        return Directory::envPath($path);
    }
}

if (! function_exists('configPath')) {
    /**
     * Helper function to get config path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function configPath(string $path = null): string
    {
        return Directory::configPath($path);
    }
}

if (! function_exists('commandsPath')) {
    /**
     * Helper function to get commands path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function commandsPath(string $path = null): string
    {
        return Directory::commandsPath($path);
    }
}

if (! function_exists('eventsPath')) {
    /**
     * Helper function to get events path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function eventsPath(string $path = null): string
    {
        return Directory::eventsPath($path);
    }
}

if (! function_exists('routesPath')) {
    /**
     * Helper function to get routes path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function routesPath(string $path = null): string
    {
        return Directory::routesPath($path);
    }
}

if (! function_exists('servicesPath')) {
    /**
     * Helper function to get services path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function servicesPath(string $path = null): string
    {
        return Directory::servicesPath($path);
    }
}

if (! function_exists('publicPath')) {
    /**
     * Helper function to get public path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function publicPath(string $path = null): string
    {
        return Directory::publicPath($path);
    }
}

if (! function_exists('resourcesPath')) {
    /**
     * Helper function to get resources path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function resourcesPath(string $path = null): string
    {
        return Directory::resourcesPath($path);
    }
}

if (! function_exists('storagePath')) {
    /**
     * Helper function to get storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function storagePath(string $path = null): string
    {
        return Directory::storagePath($path);
    }
}

if (! function_exists('frameworkStoragePath')) {
    /**
     * Helper function to get framework storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function frameworkStoragePath(string $path = null): string
    {
        return Directory::frameworkStoragePath($path);
    }
}

if (! function_exists('cachePath')) {
    /**
     * Helper function to get cache path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function cachePath(string $path = null): string
    {
        return Directory::cachePath($path);
    }
}

if (! function_exists('testsPath')) {
    /**
     * Helper function to get tests path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function testsPath(string $path = null): string
    {
        return Directory::testsPath($path);
    }
}

if (! function_exists('vendorPath')) {
    /**
     * Helper function to get vendor path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function vendorPath(string $path = null): string
    {
        return Directory::vendorPath($path);
    }
}
