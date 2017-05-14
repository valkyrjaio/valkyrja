<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Annotations;

use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Console\Command;
use Valkyrja\Container\Service;
use Valkyrja\Container\ServiceAlias;
use Valkyrja\Container\ServiceContext;
use Valkyrja\Events\Listener;
use Valkyrja\Routing\Route;

/**
 * Annotations parser class to test with.
 *
 * @author Melech Mizrachi
 */
class AnnotationsParserClass extends AnnotationsParser
{
    /**
     * Get the annotations map.
     *
     * @return array
     */
    public function getAnnotationsMap(): array
    {
        return [
            'Command'        => Command::class,
            'Listener'       => Listener::class,
            'Route'          => Route::class,
            'Service'        => Service::class,
            'ServiceAlias'   => ServiceAlias::class,
            'ServiceContext' => ServiceContext::class,
        ];
    }
}
