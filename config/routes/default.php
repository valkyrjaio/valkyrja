<?php

/*
 *-------------------------------------------------------------------------
 * Define Application Routes
 *-------------------------------------------------------------------------
 *
 * TODO: ADD EXPLANATION
 *
 */

use Valkyrja\Routing\Route;

router()->get(
    (new Route())
        ->setPath('/')
        ->setName('welcome')
        ->setClosure(
            static function () {
            }
        )
);
