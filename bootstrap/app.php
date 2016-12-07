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
 *---------------------------------------------------------------------
 * Application Environment Variables
 *---------------------------------------------------------------------
 *
 * Configuration variables are a great way to modify the application
 * and how it runs to your specific needs.
 *
 */

$envClassName = class_exists(config\Env::class)
    ? config\Env::class
    : Valkyrja\Config\Env::class;

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
    realpath(__DIR__ . '/../')
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

require_once 'container.php';

/*
 *---------------------------------------------------------------------
 * Application Configuration Variables
 *---------------------------------------------------------------------
 *
 * Configuration variables are a great way to modify the application
 * and how it runs to your specific needs.
 *
 */

$app->container()->instance(
    Valkyrja\Contracts\Config\Env::class,
    new config\Env
);

$app->container()->singleton(
    Valkyrja\Contracts\Config\Config::class,
    function () use ($app) {
        return new config\Config(
            $app->container()->get(Valkyrja\Contracts\Application::class)
        );
    }
);

$app->setTimezone();

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
 * consumers will tie in to some functionality within
 * the application to present your consumers with
 * something other than a blank screen.
 *
 */

require_once __DIR__ . '/../routes/routes.php';

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
