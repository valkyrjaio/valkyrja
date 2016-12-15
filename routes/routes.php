<?php

/**
 * Welcome Route.
 *
 * @path /
 */
get(
    '/',
    [
        'handler' => function () {
            $view = view('index')->withoutLayout();

            return response($view);
        },
    ]
);

/**
 * Framework Version Route.
 *
 * @path /version
 */
get(
    '/version',
    [
        'handler' => function () {
            return response(app()->version());
        },
    ]
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
        'action'     => 'home',
        'name'       => 'home',
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
        'action'     => 'homePaged',
        'name'       => 'homePaged',
        'injectable' => [
            // Any classes defined within the injectable array are
            //   automatically be run through the service container for you.
            Valkyrja\Application::class,
        ],
    ],
    true
);
