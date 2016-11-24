<?php

/**
 * Welcome Route.
 *
 * @path /
 */
get(
    '/',
    function () {
        $view = view('index');

        $view->setMasterTemplate('');

        return $view;
    }
);

/**
 * Framework Version Route.
 *
 * @path /version
 */
get(
    '/version',
    function () use ($app) {
        return $app->version();
    }
);

/**
 * Home Route.
 *
 * @path /home
 */
get(
    '/home',
    [
        'controller' => App\Controllers\HomeController::class,
        'action'     => 'index',
        'as'         => 'home'
    ]
);

/**
 * Home Paged Route.
 * - An example route with dependency injection and a parameter.
 *
 * @path /home/:page
 */
get(
    '\/home\/(\d+)',
    [
        'controller' => App\Controllers\HomeController::class,
        'action'     => 'indexWithParam',
        'as'         => 'homeWithParam',
        'injectable' => [
            // Any classes defined within the injectable array are
            //   automatically be run through the service container for you.
            Valkyrja\Application::class,
        ],
    ],
    true
);
