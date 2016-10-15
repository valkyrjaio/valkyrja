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
 * TODO: Fill with explanation
 *
 */

$app = new Valkyrja\Application($baseDir);

/*
 *-------------------------------------------------------------------------
 * Bind Base Container Instances
 *-------------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

$app->instance(Valkyrja\Application::class, $app);

$app->instance(
    Valkyrja\Contracts\Exceptions\HttpException::class,
    [
        function (
            $statusCode,
            $message = null,
            \Exception $previous = null,
            array $headers = [],
            $code = 0
        ) {
            return new Valkyrja\Exceptions\HttpException($statusCode, $message, $previous, $headers, $code);
        },
    ]
);

$app->instance(
    Valkyrja\Contracts\Http\Request::class,
    [
        function () {
            return new Valkyrja\Http\Request();
        },
    ]
);

$app->instance(
    Valkyrja\Contracts\Http\Response::class,
    [
        function ($content = '', $status = 200, $headers = []) {
            return new Valkyrja\Http\Response($content, $status, $headers);
        },
    ]
);

$app->instance(
    Valkyrja\Contracts\Http\JsonResponse::class,
    [
        function ($content = '', $status = 200, $headers = []) {
            return new Valkyrja\Http\JsonResponse($content, $status, $headers);
        },
    ]
);

$app->instance(
    Valkyrja\Contracts\Http\ResponseBuilder::class,
    function () use ($app) {
        $response = $app->container(Valkyrja\Contracts\Http\Response::class);
        $view = $app->container(Valkyrja\Contracts\View\View::class);

        return new Valkyrja\Http\ResponseBuilder($response, $view);
    }
);

$app->instance(
    Valkyrja\Contracts\Sessions\Session::class,
    function () {
        return new Valkyrja\Sessions\Session();
    }
);

$app->instance(
    Valkyrja\Contracts\View\View::class,
    [
        function ($template = '', array $variables = []) {
            return new Valkyrja\View\View($template, $variables);
        },
    ]
);

/*
 *-------------------------------------------------------------------------
 * Setup The Application
 *-------------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

require_once 'setup.php';

/*
 *-------------------------------------------------------------------------
 * Service Providers : Providers Of The Services
 *-------------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

$app->register(Valkyrja\Providers\TwigServiceProvider::class);
// $app->register(App\Providers\AppServiceProvider::class);

/*
 *-------------------------------------------------------------------------
 * Return The Application
 *-------------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

return $app;
