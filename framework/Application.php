<?php
/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Valkyrja;

use Valkyrja\Container\Container;
use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Exceptions\HttpException as HttpExceptionContract;
use Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\Http\Routing as RoutingContract;
use Valkyrja\Contracts\Sessions\Session as SessionContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Exceptions\ExceptionHandler;
use Valkyrja\Exceptions\HttpException;
use Valkyrja\Http\Controller;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Http\Routing;
use Valkyrja\Sessions\Session;
use Valkyrja\Support\PathHelpers;
use Valkyrja\View\View;

/**
 * Class Application
 *
 * @package Valkyrja
 *
 * @author  Melech Mizrachi
 */
class Application extends Container implements ApplicationContract, RoutingContract
{
    use ExceptionHandler;
    use Routing;
    use PathHelpers;

    /**
     * Application environment variables.
     *
     * @var array
     */
    protected $env = [];

    /**
     * Application config variables.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Application routes.
     *
     * @var array
     */
    protected $routes = [
        'simple'  => [
            self::GET    => [],
            self::POST   => [],
            self::PUT    => [],
            self::PATCH  => [],
            self::DELETE => [],
            self::HEAD   => [],
        ],
        'dynamic' => [
            self::GET    => [],
            self::POST   => [],
            self::PUT    => [],
            self::PATCH  => [],
            self::DELETE => [],
            self::HEAD   => [],
        ],
    ];

    /**
     * The base directory for the application.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Is the app using a compiled version?
     *
     * @var bool
     */
    protected $isCompiled = false;

    /**
     * Application constructor.
     *
     * @param string $basePath The base path for the application
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;

        $this->bootstrapContainer();
        $this->bootstrapHandler();
    }

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment()
    {
        return $this->config('app.env', 'production');
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug()
    {
        return $this->config('app.debug', false);
    }

    /**
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled()
    {
        return $this->config('views.twig.enabled', false);
    }

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone()
    {
        date_default_timezone_set($this->config('app.timezone', 'UTC'));
    }

    /**
     * Get whether the application is using a compiled version.
     *
     * @return bool
     */
    public function isCompiled()
    {
        return $this->isCompiled;
    }

    /**
     * Set the application as using compiled.
     *
     * @return void
     */
    public function setCompiled()
    {
        $this->isCompiled = true;
    }

    /**
     * Get a single environment variable via key or get all.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public function env($key = false, $default = false)
    {
        if (!$key) {
            return $this->env;
        }

        return isset($this->env[$key])
            ? $this->env[$key]
            : $default;
    }

    /**
     * Set a single environment variable.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return $this
     */
    public function setEnv($key, $value)
    {
        $this->env[$key] = $value;

        return $this;
    }

    /**
     * Set all environment variables.
     *
     * @param array $env The environment variables to set
     *
     * @return void
     */
    public function setEnvs(array $env)
    {
        $this->env = $env;
    }

    /**
     * Get a single config variable via key or get all.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public function config($key = false, $default = false)
    {
        if (!$key) {
            return $this->config;
        }

        return isset($this->config[$key])
            ? $this->config[$key]
            : $default;
    }

    /**
     * Set a single config variable.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return $this
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;

        return $this;
    }

    /**
     * Set all config variables.
     *
     * @param array $config The environment variables to set
     *
     * @return void
     */
    public function setConfigVars(array $config)
    {
        $this->config = $config;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @return \Valkyrja\Contracts\View\View|\Valkyrja\Http\Response|string
     *
     * @throws \Exception
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $arguments = [];
        $route = false;
        $matches = false;

        if (isset($this->routes['simple'][$requestMethod][$requestUri])) {
            $route = $this->routes['simple'][$requestMethod][$requestUri];
        }

        foreach ($this->routes['dynamic'][$requestMethod] as $path => $dynamicRoute) {
            if (preg_match('/^' . $path . '$/', $requestUri, $matches)) {
                $route = $dynamicRoute;
            }
        }

        if ($route) {
            $action = $route['action'];

            foreach ($route['injectable'] as $injectable) {
                $arguments[] = $this->container($injectable);
            }

            if ($matches && is_array($matches)) {
                foreach ($matches as $index => $match) {
                    if ($index === 0) {
                        continue;
                    }

                    $arguments[] = $match;
                }
            }

            if (is_callable($action)) {
                return call_user_func_array($action, $arguments);
            }

            $controller = $this->container($route['controller']);

            if (!$controller instanceof Controller) {
                throw new \Exception(
                    'Invalid controller for route : ' . $route['path'] . ' Controller -> ' . $route['controller']
                );
            }

            if (!is_callable(
                [
                    $controller,
                    $action,
                ]
            )
            ) {
                throw new \Exception(
                    'Action does not exist in controller for route : '
                    . $route['path']
                    . $route['controller']
                    . '@'
                    . $route['action']
                );
            }

            return call_user_func_array(
                [
                    $controller,
                    $action,
                ],
                $arguments
            );
        }

        return false;
    }

    /**
     * Abort the application due to error.
     *
     * @param int    $code    [optional] The status code to use
     * @param string $message [optional] The message or data content to use
     * @param array  $headers [optional] The headers to set
     * @param string $view    [optional] The view template name to use
     *
     * @throws HttpExceptionContract
     */
    public function abort($code = 404, $message = '', array $headers = [], $view = null)
    {
        $this->httpException($code, $message, null, $headers, $view);
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response|\Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function response($content = '', $status = 200, array $headers = [])
    {
        /** @var \Valkyrja\Contracts\Http\ResponseBuilder $factory */
        $factory = $this->container(ResponseBuilderContract::class);

        // If no args were passed return the ResponseBuilder
        if (func_num_args() === 0) {
            return $factory;
        }

        // Otherwise return a new Response using the ResponseBuilder->make() method
        return $factory->make($content, $status, $headers);
    }

    /**
     * Return a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view($template = '', array $variables = [])
    {
        return $this->container(
            ViewContract::class,
            [
                $template,
                $variables,
            ]
        );
    }

    /**
     * Run the application.
     *
     * @return void
     */
    public function run()
    {
        // Dispatch the request and get a response
        $dispatch = $this->dispatch();

        // If the dispatch failed, 404
        if (!$dispatch) {
            $this->abort(404);
        }

        // If the dispatch is a Response, send it
        if ($dispatch instanceof ResponseContract) {
            $dispatch->send();
        }
        // If the dispatch is a View, render it
        else if ($dispatch instanceof ViewContract) {
            echo (string) $dispatch->render();
        }
        // Otherwise echo it out as a string
        else {
            echo (string) $dispatch;
        }
    }

    /**
     * Register a service provider.
     *
     * @param string $serviceProvider The service provider
     *
     * @return void
     */
    public function register($serviceProvider)
    {
        // Create a new instance of the service provider
        new $serviceProvider($this);
    }

    /**
     * Bootstrap the application container.
     *
     * @return void
     */
    protected function bootstrapContainer()
    {
        /**
         * Set App instance within container.
         */
        $this->instance(Application::class, $this);

        /**
         * Set HttpException instance within container.
         */
        $this->instance(
            HttpExceptionContract::class,
            [
                function (
                    $statusCode,
                    $message = null,
                    \Exception $previous = null,
                    array $headers = [],
                    $code = 0
                ) {
                    return new HttpException($statusCode, $message, $previous, $headers, $code);
                },
            ]
        );

        /**
         * Set Request instance within container.
         */
        $this->instance(
            RequestContract::class,
            [
                function () {
                    return new Request();
                },
            ]
        );

        /**
         * Set Response instance within container.
         */
        $this->instance(
            ResponseContract::class,
            [
                function ($content = '', $status = 200, $headers = []) {
                    return new Response($content, $status, $headers);
                },
            ]
        );

        /**
         * Set JsonResponse instance within container.
         */
        $this->instance(
            JsonResponseContract::class,
            [
                function ($content = '', $status = 200, $headers = []) {
                    return new JsonResponse($content, $status, $headers);
                },
            ]
        );

        /**
         * Set ResponseBuilder instance within container.
         */
        $this->instance(
            ResponseBuilderContract::class,
            function () {
                $response = $this->container(ResponseContract::class);
                $view = $this->container(ViewContract::class);

                return new ResponseBuilder($response, $view);
            }
        );

        /**
         * Set Session instance within container.
         */
        $this->instance(
            SessionContract::class,
            function () {
                return new Session();
            }
        );

        /**
         * Set View instance within container.
         */
        $this->instance(
            ViewContract::class,
            [
                function ($template = '', array $variables = []) {
                    return new View($template, $variables);
                },
            ]
        );
    }

    /**
     * Bootstrap twig to handle views if enabled in env.
     *
     * TODO: Perhaps move this to service provider
     *
     * @return void
     */
    public function bootstrapTwig()
    {
        // Check if twig is enabled in env
        if ($this->isTwigEnabled()) {
            // Set the env variable for views directory if its not set
            $this->setConfig(
                'views.dir',
                $this->config('views.twig.dir', $this->resourcesPath('views/twig'))
            );

            // Set the env variable for views compiled directory if its not set
            $this->setConfig(
                'views.dir.compiled',
                $this->config('views.twig.dir.compiled', $this->storagePath('views/twig'))
            );

            /**
             * Set Twig_Environment instance within container.
             */
            $this->instance(
                \Twig_Environment::class,
                function () {
                    $loader = new \Twig_Loader_Filesystem($this->config('views.dir'));

                    $twig = new \Twig_Environment(
                        $loader, [
                                   'cache'   => $this->config('views.dir.compiled'),
                                   'debug'   => $this->debug(),
                                   'charset' => 'utf-8',
                               ]
                    );

                    $extensions = $this->config('views.twig.extensions');

                    // Twig Extensions registration
                    if (is_array($extensions)) {
                        foreach ($extensions as $extension) {
                            $twig->addExtension(new $extension());
                        }
                    }

                    return $twig;
                }
            );

            /**
             * Reset View instance within container for TwigView.
             */
            $this->instance(
                ViewContract::class,
                [
                    function ($template = '', array $variables = []) {
                        $view = new \Valkyrja\View\TwigView($template, $variables);

                        $view->setTwig($this->container(\Twig_Environment::class));

                        return $view;
                    },
                ]
            );
        }
    }
}
