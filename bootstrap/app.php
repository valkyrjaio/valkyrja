<?php

/*
 *-------------------------------------------------------------------------
 * Set The Base Directory
 *-------------------------------------------------------------------------
 *
 * Let's set the base directory within the web server for our application
 * so that when we locate directories and files within the application
 * we have a standard location from which to do so.
 *
 */

Valkyrja\Support\Directory::$BASE_PATH = realpath(__DIR__ . '/../');

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

$app = new Valkyrja\Application(
// Set the container
    new Valkyrja\Container\Container(),
    // Set the config
    new config\Config(
    // With environment variables
        new config\Env()
    )
);

/*
 *---------------------------------------------------------------------
 * Application Service Container : Dependency Injection
 *---------------------------------------------------------------------
 *
 * Adding more instances to the service container is a great way to
 * ensure your application is setup for ease of change in the
 * future.
 *
 */

require __DIR__ . '/container.php';

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
 * users will visit, and tie it into some functionality
 * within the application to present your users with
 * something other than a blank screen.
 *
 */

$app->router()->setup();
// (new \Valkyrja\Console\Routing())->run();

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
