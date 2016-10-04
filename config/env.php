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
    'app.env'            => 'local',
    'app.debug'          => false,
    'app.url'            => 'app.dev',
    'app.timezone'       => 'UTC',
    'app.version'        => '1 (ALPHA)',

    /*
    |--------------------------------------------------------------------------
    | View Specific Environment Variables
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    'views.dir'          => $app->resourcesPath('views/php'),
    'views.dir.compiled' => $app->storagePath('views/php'),
    'views.twig.enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Storage Specific Environment Variables
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    'uploads.dir'        => $app->storagePath('app'),
    'logs.dir'           => $app->storagePath('logs'),

    /*
    |--------------------------------------------------------------------------
    | Application Models
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    'models.article'     => \App\Models\Article::class,
    'models.user'        => \App\Models\User::class,
];
