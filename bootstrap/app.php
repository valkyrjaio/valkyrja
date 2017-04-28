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
    // Set the events
    new Valkyrja\Events\Events(),
    // Set the config
    new config\Config(
    // With environment variables
        new config\Env()
    )
);

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
