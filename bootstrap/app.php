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

return new Valkyrja\Application(
    new config\Config(
        new config\Env()
    )
);
