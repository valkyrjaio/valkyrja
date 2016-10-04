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

use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Http\Controller;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\View\View;

/**
 * Class Application
 *
 * @package Valkyrja
 *
 * @author  Melech Mizrachi
 */
class Application
{
    /**
     * The Application framework version.
     *
     * @constant string
     */
    const VERSION = '1.0.0 (ALPHA)';

    /**
     * Route constants.
     *
     * @constant
     */
    const GET    = 'GET';
    const POST   = 'POST';
    const PUT    = 'PUT';
    const PATCH  = 'PATCH';
    const DELETE = 'DELETE';
    const HEAD   = 'HEAD';

    /**
     * Directory separator.
     *
     * @constant string
     */
    const DIRECTORY_SEPARATOR = '/';

    /**
     * Application environment variables.
     *
     * @var array
     */
    protected $env = [];

    /**
     * Application service container for dependency injection.
     *
     * @var array
     */
    protected $serviceContainer = [];

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

        // Setup the auto loader for the Valkyrja namespace
        // - Using our own auto loading for better optimization
        $this->autoloader();
        $this->bootstrapContainer();
        $this->bootstrapHandler();
    }

    /**
     * Bootstrap the application container.
     *
     * @return void
     */
    protected function bootstrapContainer()
    {
        $this->instance('app', $this);
        $this->instance(Application::class, $this);

        $this->instance(
            RequestContract::class,
            [
                function () {
                    return new Request();
                },
            ]
        );

        $this->instance(
            ResponseContract::class,
            [
                function ($content = '', $status = 200, $headers = []) {
                    return new Response($content, $status, $headers);
                },
            ]
        );

        $this->instance(
            JsonResponseContract::class,
            [
                function ($content = '', $status = 200, $headers = []) {
                    return new JsonResponse($content, $status, $headers);
                },
            ]
        );

        $this->instance(
            ResponseBuilderContract::class,
            function () {
                $response = container(ResponseContract::class);
                $view = container(ViewContract::class);

                return new ResponseBuilder($response, $view);
            }
        );

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
     * @return void
     */
    public function bootstrapTwig()
    {
        // Check if twig is enabled in env
        if ($this->isTwigEnabled()) {
            // Set the twig auto loader
            // - Using our own auto loading for better optimization
            $this->autoloader('Twig_', $this->vendorPath('twig/twig/lib/Twig'), '_');

            // Set the env variable for views directory if its not set
            $twigDir = $this->env('views.twig.dir', false);
            $this->setEnv(
                'views.dir',
                $twigDir
                    ?: $this->resourcesPath('views/twig')
            );

            // Set the env variable for views compiled directory if its not set
            $twigCompiledDir = $this->env('views.twig.dir.compiled', false);
            $this->setEnv(
                'views.dir.compiled',
                $twigCompiledDir
                    ?: storagePath('views/twig')
            );

            // Set the Twig_Environment class in the service container
            $this->instance(
                \Twig_Environment::class,
                function () {
                    $loader = new \Twig_Loader_Filesystem($this->env('views.dir'));

                    $twig = new \Twig_Environment(
                        $loader, [
                                   'cache' => $this->env('views.dir.compiled'),
                               ]
                    );

                    // Twig Extensions Here
                    // $twig->addExtension(new \App\Views\Extensions\TwigStaticExtension());

                    return $twig;
                }
            );

            // Set the View class in the service container as Twig view
            $this->instance(
                ViewContract::class,
                [
                    function ($template = '', array $variables = []) {
                        $view = new \Valkyrja\View\TwigView($template, $variables);

                        $view->setTwig(container(\Twig_Environment::class));

                        return $view;
                    },
                ]
            );
        }
    }

    /**
     * Bootstrap error, exception, and shutdown handler.
     *
     * @return void
     */
    protected function bootstrapHandler()
    {
        error_reporting(-1);

        set_error_handler(
            [
                $this,
                'handleError',
            ]
        );

        set_exception_handler(
            [
                $this,
                'handleException',
            ]
        );

        register_shutdown_function(
            [
                $this,
                'handleShutdown',
            ]
        );

        if (!$this->debug()) {
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param int    $level   The error level
     * @param string $message The error message
     * @param string $file    The file within which the error occurred
     * @param int    $line    The line which threw the error
     * @param array  $context
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param \Throwable $e The exception that was captured
     *
     * @return ResponseContract
     */
    public function handleException($e)
    {
        if (!$e instanceof \Exception) {
            $e = new \Exception($e);
        }

        $data = [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTrace(),
        ];

        return $this->abort(500, $data, [], 'errors/exception');
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown()
    {
        if (!is_null($error = error_get_last())
            && in_array(
                $error['type'],
                [
                    E_ERROR,
                    E_CORE_ERROR,
                    E_COMPILE_ERROR,
                    E_PARSE,
                ]
            )
        ) {
            $this->handleException($this->fatalExceptionFromError($error));
        }
    }

    /**
     * Create a new fatal exception instance from an error array.
     *
     * @param array $error The error array to use
     *
     * @return \Exception
     */
    protected function fatalExceptionFromError(array $error)
    {
        return new \ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
        );
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment()
    {
        return $this->env('app.env', 'production');
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug()
    {
        return $this->env('app.debug', false);
    }

    /**
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled()
    {
        return $this->env('views.twig.enabled', false);
    }

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone()
    {
        date_default_timezone_set($this->env('app.timezone', 'UTC'));
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
     * @return bool
     */
    public function setCompiled()
    {
        $this->isCompiled = true;
    }

    /**
     * Get an environment variable via key.
     *
     * @param string|bool $key     The variable to get
     * @param mixed       $default Default value to return if not found
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
     * @return void
     */
    public function setEnv($key, $value)
    {
        $this->env[$key] = $value;
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
     * Get the base directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function basePath($path = null)
    {
        return $this->basePath . ($path
            ? static::DIRECTORY_SEPARATOR . $path
            : $path);
    }

    /**
     * Get the app directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function appPath($path = null)
    {
        return $this->basePath(
            'app' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the cache directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function cachePath($path = null)
    {
        return $this->basePath(
            'cache' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the config directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function configPath($path = null)
    {
        return $this->basePath(
            'config' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the framework directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function frameworkPath($path = null)
    {
        return $this->basePath(
            'framework' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the public directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function publicPath($path = null)
    {
        return $this->basePath(
            'public' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the resources directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function resourcesPath($path = null)
    {
        return $this->basePath(
            'resources' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the storage directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function storagePath($path = null)
    {
        return $this->basePath(
            'storage' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the tests directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function testsPath($path = null)
    {
        return $this->basePath(
            'tests' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Get the vendor directory for the application.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    public function vendorPath($path = null)
    {
        return $this->basePath(
            'vendor' . ($path
                ? static::DIRECTORY_SEPARATOR . $path
                : $path)
        );
    }

    /**
     * Set a single route.
     *
     * @param string         $method    The method type (GET, POST, PUT, PATCH, DELETE, HEAD)
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function addRoute($method, $path, $handler, $isDynamic = false)
    {
        if (!in_array(
            $method,
            [
                self::GET,
                self::POST,
                self::PUT,
                self::PATCH,
                self::DELETE,
                self::HEAD,
            ]
        )
        ) {
            throw new \Exception('Invalid method type for route: ' . $path);
        }

        $isArray = is_array($handler);

        $name = ($isArray && isset($handler['as']))
            ? $handler['as']
            : $path;

        if (is_callable($handler)) {
            $action = $handler;
            $controller = false;
            $injectable = false;
        }
        else {
            $controller = ($isArray && isset($handler['controller']))
                ? $handler['controller']
                : false;

            $action = ($isArray && isset($handler['action']))
                ? $handler['action']
                : false;

            $injectable = ($isArray && isset($handler['injectable']))
                ? $handler['injectable']
                : [];

            if (!$action) {
                throw new \Exception('No action or handler set for route: ' . $path);
            }
        }

        $route = [
            'path'       => $path,
            'as'         => $name,
            'controller' => $controller,
            'action'     => $action,
            'injectable' => $injectable,
        ];

        // Set the route
        if ($isDynamic) {
            $this->routes['dynamic'][$method][$path] = $route;
        }
        else {
            $this->routes['simple'][$method][$path] = $route;
        }
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function get($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::GET, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function post($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::POST, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function put($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::PUT, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function patch($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::PATCH, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function delete($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::DELETE, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function head($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::HEAD, $path, $handler, $isDynamic);
    }

    /**
     * Set routes from a given array of routes.
     *
     * @param array $routes The routes to set
     *
     * @return void
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
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
     * @param int    $code    The status code to use
     * @param string $message The message or data content to use
     * @param array  $headers The headers to set
     * @param string $view    The view template name to use
     *
     * @return void
     */
    public function abort($code = 404, $message = '', array $headers = [], $view = null)
    {
        // Set the view to use
        $view = $view
            ?: 'errors/' . $code;

        // If the message is a string the view expects an array
        if (is_string($message)) {
            $message = ['message' => $message];
        }

        // Return a new sent response
        $this->response()
                    ->view($view, $message, $code, $headers)
                    ->send();

        // Kill the application
        die(1);
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content The content to set
     * @param int    $status  The status code to set
     * @param array  $headers The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response|\Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function response($content = '', $status = 200, array $headers = [])
    {
        /** @var \Valkyrja\Contracts\Http\ResponseBuilder $factory */
        $factory = container(ResponseBuilderContract::class);

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
     * @param string $template  The template to use
     * @param array  $variables The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view($template = '', array $variables = [])
    {
        return container(
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
     * Set the service container for dependency injection.
     *
     * @param array $serviceContainer The service container array to set
     *
     * @return void
     */
    public function setServiceContainer(array $serviceContainer)
    {
        // The application has already bootstrapped the container so merge to avoid clearing
        $this->serviceContainer = array_merge($this->serviceContainer, $serviceContainer);
    }

    /**
     * Set the service container for dependency injection.
     *
     * @param string               $abstract The abstract to use as the key
     * @param \Closure|array|mixed $instance The instance to set
     *
     * @return void
     */
    public function instance($abstract, $instance)
    {
        $this->serviceContainer[$abstract] = $instance;
    }

    /**
     * Get an abstract from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments Arguments to pass
     *
     * @return mixed
     */
    public function container($abstract, array $arguments = [])
    {
        // If the abstract is set in the service container
        if (isset($this->serviceContainer[$abstract])) {
            // Set the container item for ease of use here
            $containerItem = $this->serviceContainer[$abstract];

            // The container item is a singleton and hasn't been requested yet
            if (is_callable($containerItem)) {
                // Run the callable function
                $containerItem = $containerItem();

                // Set the result in the service container for the next request
                $this->serviceContainer[$abstract] = $containerItem;

                // Return the container item
                return $containerItem;
            }
            // Otherwise we're looking to get a new instance every time
            elseif (is_array($containerItem) && is_callable($containerItem[0])) {
                // Return the first item in the array
                return call_user_func_array($containerItem[0], $arguments);
            }

            // Return the container item
            return $containerItem;
        }

        // A class was passed just in case it was in the container, so return it
        return $abstract;
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
     * Application autoloader.
     *
     * @param string $prefix      The prefix to register
     * @param string $baseDir     The base directory to look under
     * @param string $deliminator The deliminator to replace to a directory separator
     *
     * @return void
     */
    public function autoloader($prefix = 'Valkyrja\\', $baseDir = null, $deliminator = '\\')
    {
        $baseDir = $baseDir
            ?: $this->frameworkPath();

        // Register a new autoload closure
        spl_autoload_register(
            function ($class) use ($prefix, $baseDir, $deliminator) {
                // does the class use the namespace prefix?
                $len = strlen($prefix);

                if (strncmp($prefix, $class, $len) !== 0) {
                    // no, move to the next registered autoloader
                    return;
                }

                // get the relative class name
                $relative_class = substr($class, $len);

                // replace the namespace prefix with the base directory, replace namespace
                // separators with directory separators in the relative class name, append
                // with .php
                $file = $baseDir . static::DIRECTORY_SEPARATOR . str_replace(
                        $deliminator,
                        static::DIRECTORY_SEPARATOR,
                        $relative_class
                    ) . '.php';

                // if the file exists, require it
                if (file_exists($file)) {
                    require $file;
                }
            }
        );
    }
}
