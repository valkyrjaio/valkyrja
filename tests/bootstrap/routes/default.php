<?php

declare(strict_types=1);

/**
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Routing\Collector;

/** @var Collector $collector */

$collector->group(static function (Collector $collector) {
    $collector->get(
        '/',
        static function () {
        },
        'welcome'
    );
});
