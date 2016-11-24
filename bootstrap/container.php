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
 * Bind Base Container Instances
 *-------------------------------------------------------------------------
 *
 * Important classes and service container instances that will help the
 * entire application function are defined here for better ease of
 * change by the developer.
 *
 */

$container->instance(
    Valkyrja\Contracts\Application::class,
    $app
);

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

/*
 *-------------------------------------------------------------------------
 * Bind Application Container Instances
 *-------------------------------------------------------------------------
 *
 * TODO: ADD EXPLANATION
 *
 */

$container->instance(
    App\Controllers\HomeController::class,
    [
        function () use ($app) {
            return new App\Controllers\HomeController($app);
        },
    ]
);
