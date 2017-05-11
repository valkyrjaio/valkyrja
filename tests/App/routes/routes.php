<?php

use Valkyrja\Routing\Route;

/**
 * Welcome Route.
 * - Example of a view being returned
 *
 * @path /
 */
router()->get(
    (new Route())
        ->setPath('/')
        ->setName('home.welcome')
        ->setClosure(
            function (): Valkyrja\Contracts\View\View {
                return view('index')->withoutLayout();
            }
        )
);

/**
 * Framework Version Route.
 * - Example of string being returned
 *
 * @path /version
 */
router()->get(
    (new Route())
        ->setPath('/version')
        ->setName('version')
        ->setClosure(
            function (): string {
                return app()->version();
            }
        )
);

/**
 * Property Routing Example Route.
 * - Example of string being returned from a property
 *
 * @path /property
 */
router()->get(
    (new Route())
        ->setPath('/property')
        ->setName('property')
        ->setClass(App\Controllers\HomeController::class)
        ->setProperty('propertyRouting')
);

/**
 * Home Route.
 * - Example with multiple routes to the same action
 *
 * @path /home
 */
router()->get(
    (new Route())
        ->setPath('/home')
        ->setName('home')
        ->setClass(App\Controllers\HomeController::class)
        ->setMethod('home')
        ->setDependencies(
            [
                // Any classes defined within the injectable array are
                //   automatically be run through the service container for you.
                Valkyrja\Contracts\Application::class,
            ]
        )
);

/**
 * Home Paged Route.
 * - An example route with dependency injection and a parameter.
 * - Example with multiple routes to the same action
 *
 * @path /home/:page
 */
router()->get(
    (new Route())
        ->setPath('/home/{id:num}')
        ->setName('homePage')
        ->setClass(App\Controllers\HomeController::class)
        ->setMethod('home')
        ->setDependencies(
            [
                // Any classes defined within the injectable array are
                //   automatically be run through the service container for you.
                Valkyrja\Contracts\Application::class,
            ]
        )
        ->setDynamic(true)
);
