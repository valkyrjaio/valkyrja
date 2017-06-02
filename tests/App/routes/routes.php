<?php

use Valkyrja\Routing\Route;

/*
 * Welcome Route.
 * - Example of a view being returned
 *
 * @path /
 */
router()->get(
    (new Route())
        ->setPath('/')
        ->setName('home.welcome')
        ->setClass(Valkyrja\Tests\App\App\Controllers\HomeController::class)
        ->setProperty('welcome')
);

/*
 * Framework Version Route.
 * - Example of string being returned
 *
 * @path /version
 */
router()->get(
    (new Route())
        ->setPath('/version')
        ->setName('home.version')
        ->setClass(Valkyrja\Tests\App\App\Controllers\HomeController::class)
        ->setMethod('version')
);

/*
 * Property Routing Example Route.
 * - Example of string being returned from a property
 *
 * @path /property
 */
router()->get(
    (new Route())
        ->setPath('/property')
        ->setName('home.property')
        ->setClass(Valkyrja\Tests\App\App\Controllers\HomeController::class)
        ->setMethod('propertyRouting')
);
