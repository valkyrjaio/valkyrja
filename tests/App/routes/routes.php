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
        ->setName('welcome')
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
        ->setName('version')
        ->setClass(Valkyrja\Tests\App\App\Controllers\HomeController::class)
        ->setProperty('version')
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
        ->setName('property')
        ->setClass(Valkyrja\Tests\App\App\Controllers\HomeController::class)
        ->setProperty('propertyRouting')
);
