<?php

/**
 * Welcome Route.
 * - Example of a view being returned
 *
 * @path /
 */
use Valkyrja\Routing\Route;

router()->get(
    (new Route())
        ->setPath('/')
        ->setHandler(
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
        ->setHandler(
            function (): string {
                return app()->version();
            }
        )
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
        ->setController(App\Controllers\HomeController::class)
        ->setAction('home')
        ->setName('home')
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
        ->setController(App\Controllers\HomeController::class)
        ->setAction('home')
        ->setName('homePage')
        ->setDependencies(
            [
                // Any classes defined within the injectable array are
                //   automatically be run through the service container for you.
                Valkyrja\Contracts\Application::class,
            ]
        )
    ->setDynamic(true)
);
