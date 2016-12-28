<?php

/**
 * Welcome Route.
 * - Example of a view being returned
 *
 * @path /
 */
get(
    '/',
    [
        'handler' => function (): Valkyrja\Contracts\View\View {
            return view('index')->withoutLayout();
        },
    ]
);

/**
 * Framework Version Route.
 * - Example of string being returned
 *
 * @path /version
 */
get(
    '/version',
    [
        'handler' => function (): string {
            return app()->version();
        },
    ]
);

/**
 * Home Route.
 * - Example with multiple routes to the same action
 *
 * @path /home
 */
get(
    '/home',
    [
        'controller' => App\Controllers\HomeController::class,
        'action'     => 'home',
        'name'       => 'home',
        'injectable' => [
            // Any classes defined within the injectable array are
            //   automatically be run through the service container for you.
            Valkyrja\Contracts\Application::class,
        ],
    ]
);

/**
 * Home Paged Route.
 * - An example route with dependency injection and a parameter.
 * - Example with multiple routes to the same action
 *
 * @path /home/:page
 */
get(
    '/home/{id:num}',
    [
        'controller' => App\Controllers\HomeController::class,
        'action'     => 'home',
        'name'       => 'homePage',
        'injectable' => [
            // Any classes defined within the injectable array are
            //   automatically be run through the service container for you.
            Valkyrja\Contracts\Application::class,
        ],
    ],
    true
);
