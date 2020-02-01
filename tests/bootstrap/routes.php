<?php

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
