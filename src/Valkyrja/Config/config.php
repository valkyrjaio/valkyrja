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
 * Framework Default Configurations
 *-------------------------------------------------------------------------
 *
 * We'll need to run the application somehow, and so we'll need certain
 * configuration settings in order to set everything up correctly,
 * and appropriately. Here we have all the configurations for
 * the application, including configurations for each module
 * included in the framework.
 *
 */
return [
    /*
     *-------------------------------------------------------------------------
     * Application Configuration
     *-------------------------------------------------------------------------
     *
     * This part of the configuration has to do with the base configuration
     * settings for the application as a whole.
     *
     */
    'app'           => require __DIR__ . '/app.php',

    /*
     *-------------------------------------------------------------------------
     * Annotations Configuration
     *-------------------------------------------------------------------------
     *
     * Anything and everything to do with annotations and how they are
     * configured to work within the application can be found here.
     *
     */
    'annotations'   => require __DIR__ . '/annotations.php',

    /*
     *-------------------------------------------------------------------------
     * Console Configuration
     *-------------------------------------------------------------------------
     *
     * The console is Valkyrja's module for working with the application
     * through the CLI. All the configurations necessary to make that
     * work can be found here.
     *
     */
    'console'       => require __DIR__ . '/console.php',

    /*
     *-------------------------------------------------------------------------
     * Container Configuration
     *-------------------------------------------------------------------------
     *
     * The container is the go to place for any type of service the
     * application may need when it is running. All configurations
     * necessary to make it run correctly can be found here.
     *
     */
    'container'     => require __DIR__ . '/container.php',

    /*
     *-------------------------------------------------------------------------
     * Database Configuration
     *-------------------------------------------------------------------------
     *
     * Persist your application's data through a data store using a database
     * connection method. All configurations for getting you going with
     * a few different data stores is available here.
     *
     */
    'database'      => require __DIR__ . '/database.php',

    /*
     *-------------------------------------------------------------------------
     * Events Configuration
     *-------------------------------------------------------------------------
     *
     * Events are a nifty way to tie into certain happenings throughout the
     * application. Found here are all the configurations required to make
     * events work without a hitch.
     *
     */
    'events'        => require __DIR__ . '/events.php',

    /*
     *-------------------------------------------------------------------------
     * Filesystem Configuration
     *-------------------------------------------------------------------------
     *
     * How the application stores, retrieves, copies, and manipulates files
     * across the filesystem it is located within is a necessity in most
     * applications. Configure that manipulative module here.
     *
     */
    'filesystem'    => require __DIR__ . '/filesystem.php',

    /*
     *-------------------------------------------------------------------------
     * Logger Configuration
     *-------------------------------------------------------------------------
     *
     * Logging is very helpful in understanding what occurs within your
     * application when its deployed and used by multiple users aside
     * from you and your developers. Configure that helpfulness here.
     *
     */
    'logger'        => require __DIR__ . '/logger.php',

    /*
     *-------------------------------------------------------------------------
     * Routing Configuration
     *-------------------------------------------------------------------------
     *
     * A pretty big part of getting a user what they've requested is being
     * able to properly route a request through your application. In
     * order to do that you'll need to configure it. Lucky for you
     * all the configurations for routing can be found here.
     *
     */
    'routing'       => require __DIR__ . '/routing.php',

    /*
     *-------------------------------------------------------------------------
     * Session Configuration
     *-------------------------------------------------------------------------
     *
     * You'll need to keep track of some stuff across requests, and that's
     * where the session comes in handy. Here you'll find all necessary
     * configurations to make the session work properly.
     *
     */
    'session'       => require __DIR__ . '/session.php',

    /*
     *-------------------------------------------------------------------------
     * Views Configuration
     *-------------------------------------------------------------------------
     *
     * Views are what provide users with something to look at and enjoy all
     * the hard work you've put into the application. Here you'll find
     * all the configurations necessary to make that work properly.
     *
     */
    'views'         => require __DIR__ . '/views.php',

    /*
     *-------------------------------------------------------------------------
     * Config Providers
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'providers'     => env('CONFIG_PROVIDERS', []),

    /*
     *-------------------------------------------------------------------------
     * Config File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'filePath'      => env('CONFIG_FILE_PATH', configPath('config.php')),

    /*
     *-------------------------------------------------------------------------
     * Config Cache File Path
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'cacheFilePath' => env('CONFIG_CACHE_FILE_PATH', cachePath('config.php')),

    /*
     *-------------------------------------------------------------------------
     * Config Use Cache File
     *-------------------------------------------------------------------------
     *
     * //
     *
     */
    'useCache'      => env('CONFIG_USE_CACHE_FILE', false),
];
