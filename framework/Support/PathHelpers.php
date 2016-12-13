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
 * Class PathHelpers
 *
 * @package Valkyrja\Support
 *
 * @author  Melech Mizrachi
 */
trait PathHelpers
{
    /**
     * Get the base directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function basePath(string $path = null) : string
    {
        return Directory::$BASE_PATH . ($path
            ? Directory::DIRECTORY_SEPARATOR . $path
            : $path);
    }

    /**
     * Get the app directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function appPath(string $path = null) : string
    {
        return $this->basePath(
            'app' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the cache directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function cachePath(string $path = null) : string
    {
        return $this->basePath(
            'cache' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
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
    public function configPath(string $path = null) : string
    {
        return $this->basePath(
            'config' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
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
    public function frameworkPath(string $path = null) : string
    {
        return $this->basePath(
            'framework' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
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
    public function publicPath(string $path = null) : string
    {
        return $this->basePath(
            'public' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
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
    public function resourcesPath(string $path = null) : string
    {
        return $this->basePath(
            'resources' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
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
    public function storagePath(string $path = null) : string
    {
        return $this->basePath(
            'storage' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
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
    public function testsPath(string $path = null) : string
    {
        return $this->basePath(
            'tests' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
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
    public function vendorPath(string $path = null) : string
    {
        return $this->basePath(
            'vendor' . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }
}
