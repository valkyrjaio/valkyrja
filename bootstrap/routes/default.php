<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 *-------------------------------------------------------------------------
 * Define Application Routes
 *-------------------------------------------------------------------------
 *
 * TODO: ADD EXPLANATION
 *
 */

use Valkyrja\Routing\Models\Route;

router()->get(
    (new Route())
        ->setPath('/')
        ->setName('welcome')
        ->setClosure(
            static function () {
            }
        )
);
