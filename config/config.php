<?php
/*
 * This file is part of the Valkyrja framework.
 *
 * It is used to set the application environment variables.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Application Specific Environment Variables
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    'app.env'                 => $app->env('app.env', 'production'),
    'app.debug'               => $app->env('app.debug', false),
    'app.url'                 => $app->env('app.url', 'app.com'),
    'app.timezone'            => $app->env('app.timezone', 'UTC'),
    'app.version'             => $app->env('app.version', '1.0'),

    /*
    |--------------------------------------------------------------------------
    | View Specific Environment Variables
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    'views.dir'               => $app->env('views.dir', $app->resourcesPath('views/php')),
    'views.dir.compiled'      => $app->env('views.dir.compiled', $app->storagePath('views/php')),
    'views.twig.enabled'      => $app->env('views.twig.enabled', false),
    'views.twig.dir'          => $app->env('views.twig.dir', $app->resourcesPath('views/twig')),
    'views.twig.dir.compiled' => $app->env('views.twig.dir.compiled', $app->storagePath('views/twig')),

    /*
    |--------------------------------------------------------------------------
    | Storage Specific Environment Variables
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    'uploads.dir'             => $app->env('uploads.dir', $app->storagePath('app')),
    'logs.dir'                => $app->env('logs.dir', $app->storagePath('logs')),

    /*
    |--------------------------------------------------------------------------
    | Application Models
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    'models.article'          => \App\Models\Article::class,
    'models.user'             => \App\Models\User::class,
];
