<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts;

use Valkyrja\Contracts\Container\Container;

/**
 * Interface Application
 *
 * @package Valkyrja\Contracts
 *
 * @author  Melech Mizrachi
 */
interface Application extends Container
{
    /**
     * The Application framework version.
     *
     * @constant string
     */
    const VERSION = 'Valkyrja (1.0.0 Alpha)';

    /**
     * Application constructor.
     *
     * @param string $basePath The base path for the application
     */
    public function __construct($basePath);

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version();

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment();

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug();

    /**
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled();

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone();

    /**
     * Get whether the application is using a compiled version.
     *
     * @return bool
     */
    public function isCompiled();

    /**
     * Set the application as using compiled.
     *
     * @return void
     */
    public function setCompiled();

    /**
     * Get a single environment variable via key or get all.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public function env($key = false, $default = false);

    /**
     * Set a single environment variable.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return $this
     */
    public function setEnv($key, $value);

    /**
     * Set all environment variables.
     *
     * @param array $env The environment variables to set
     *
     * @return void
     */
    public function setEnvs(array $env);

    /**
     * Get a single config variable via key or get all.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public function config($key = false, $default = false);

    /**
     * Set a single config variable.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return $this
     */
    public function setConfig($key, $value);

    /**
     * Set all config variables.
     *
     * @param array $config The environment variables to set
     *
     * @return void
     */
    public function setConfigVars(array $config);

    /**
     * Get the base directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function basePath($path = null);

    /**
     * Get the app directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function appPath($path = null);

    /**
     * Get the cache directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function cachePath($path = null);

    /**
     * Get the config directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function configPath($path = null);

    /**
     * Get the framework directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function frameworkPath($path = null);

    /**
     * Get the public directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function publicPath($path = null);

    /**
     * Get the resources directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function resourcesPath($path = null);

    /**
     * Get the storage directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function storagePath($path = null);

    /**
     * Get the tests directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function testsPath($path = null);

    /**
     * Get the vendor directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function vendorPath($path = null);

    /**
     * Abort the application due to error.
     *
     * @param int    $code    [optional] The status code to use
     * @param string $message [optional] The message or data content to use
     * @param array  $headers [optional] The headers to set
     * @param string $view    [optional] The view template name to use
     *
     * @throws \Valkyrja\Contracts\Exceptions\HttpException
     */
    public function abort($code = 404, $message = '', array $headers = [], $view = null);

    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response|\Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function response($content = '', $status = 200, array $headers = []);

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public function router();

    /**
     * Return a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view($template = '', array $variables = []);

    /**
     * Run the application.
     *
     * @return void
     */
    public function run();

    /**
     * Register a service provider.
     *
     * @param string $serviceProvider The service provider
     *
     * @return void
     */
    public function register($serviceProvider);
}
