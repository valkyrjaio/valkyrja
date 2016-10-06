<?php
/*
 * This file is part of the Valkyrja framework.
 *
 * It is used to set the application routes.
 */

/**
 * Framework Version Route
 *
 * @path /version
 */
$app->get(
    '/version',
    function () use ($app) {
        return $app->version();
    }
);

/*
|--------------------------------------------------------------------------
| Homepage Routes
|--------------------------------------------------------------------------
|
| TODO: Fill with explanation
|
*/
/**
 * Home Route
 *
 * @path /
 */
$app->get(
    '/',
    [
        'controller' => \App\Controllers\HomeController::class,
        'action'     => 'index',
        'as'         => 'home',
        'injectable' => [],
    ]
);

/**
 * Home Paged Route
 *
 * @path /:page
 */
$app->get(
    '\/(\d+)',
    [
        'controller' => \App\Controllers\HomeController::class,
        'action'     => 'paged',
        'as'         => 'homePaged',
        'injectable' => [
            // Any classes defined within the injectable array are
            //   automatically be run through the service container for you.
            \Valkyrja\Application::class,
        ],
    ],
    true
);

/*
|--------------------------------------------------------------------------
| Article Routes
|--------------------------------------------------------------------------
|
| TODO: Fill with explanation
|
*/
/**
 * Article Route
 *
 * @path /article/:slug
 *       Slug is alphanumeric with dashes and underscores allowed
 */
$app->get(
    '\/article\/([a-zA-Z0-9-_]+)',
    [
        'controller' => \App\Controllers\ArticleController::class,
        'action'     => 'index',
        'as'         => 'article',
        'injectable' => [],
    ],
    true
);
