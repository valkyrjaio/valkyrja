<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 *-------------------------------------------------------------------------
 * Start Up The Application
 *-------------------------------------------------------------------------
 *
 * Let's start up the application by creating a new instance of the
 * application class. This is going to bind all the various
 * components together into a singular hub.
 *
 */

$app = new Valkyrja\Application($baseDir);
$container = new Valkyrja\Container\Container();

/*
 *-------------------------------------------------------------------------
 * Configure The Application
 *-------------------------------------------------------------------------
 *
 * Configure the application with environment variables, config variables,
 * routes, and custom service container instances.
 *
 */

require_once 'configuration.php';

/*
 *-------------------------------------------------------------------------
 * Bind Base Container Instances
 *-------------------------------------------------------------------------
 *
 * Important classes and service container instances that will help the
 * entire application function are defined here for better ease of
 * change by the developer.
 *
 */

$container->instance(Valkyrja\Application::class, $app);

$container->instance(
    Valkyrja\Contracts\Exceptions\HttpException::class,
    [
        function (
            $statusCode,
            $message = null,
            Exception $previous = null,
            array $headers = [],
            $code = 0
        ) {
            return new Valkyrja\Exceptions\HttpException($statusCode, $message, $previous, $headers, $code);
        },
    ]
);

$container->instance(
    Valkyrja\Contracts\Http\Request::class,
    [
        function () {
            return new Valkyrja\Http\Request();
        },
    ]
);

$container->instance(
    Valkyrja\Contracts\Http\Response::class,
    [
        function ($content = '', $status = 200, $headers = []) {
            return new Valkyrja\Http\Response($content, $status, $headers);
        },
    ]
);

$container->instance(
    Valkyrja\Contracts\Http\JsonResponse::class,
    [
        function ($content = '', $status = 200, $headers = []) {
            return new Valkyrja\Http\JsonResponse($content, $status, $headers);
        },
    ]
);

$container->instance(
    Valkyrja\Contracts\Http\ResponseBuilder::class,
    function () use ($container) {
        $response = $container->get(Valkyrja\Contracts\Http\Response::class);
        $view = $container->get(Valkyrja\Contracts\View\View::class);

        return new Valkyrja\Http\ResponseBuilder($response, $view);
    }
);

$container->instance(
    Valkyrja\Contracts\Http\Router::class,
    function () use ($container) {
        $application = $container->get(Valkyrja\Application::class);

        return new Valkyrja\Http\Router($application);
    }
);

$container->instance(
    Valkyrja\Contracts\Sessions\Session::class,
    function () {
        return new Valkyrja\Sessions\Session();
    }
);

$container->instance(
    Valkyrja\Contracts\View\View::class,
    [
        function ($template = '', array $variables = []) {
            return new Valkyrja\View\View($template, $variables);
        },
    ]
);

$app->bootstrapHandler();

/*
 *-------------------------------------------------------------------------
 * Service Providers : Providers Of The Services
 *-------------------------------------------------------------------------
 *
 * Service providers are a convenience way to add more functionality to
 * the application by registering new service container instances,
 * configuration options, or other functional needs your
 * application may need.
 *
 */

$app->register(Valkyrja\Providers\TwigServiceProvider::class);
// $app->register(App\Providers\AppServiceProvider::class);

/*
 *---------------------------------------------------------------------
 * Application Routes
 *---------------------------------------------------------------------
 *
 * Match those silly strings in the url that your application's
 * consumers will tie in to some functionality within
 * the application to present your consumers with
 * something other than a blank screen.
 *
 */

// Require the routes
$routes = require_once __DIR__ . '/../routes/routes.php';

/*
 *-------------------------------------------------------------------------
 * Return The Application
 *-------------------------------------------------------------------------
 *
 * Well, we kind of have to use the application after bootstrapping, so
 * let's return it back to the index file.
 *
 */

return $app;
