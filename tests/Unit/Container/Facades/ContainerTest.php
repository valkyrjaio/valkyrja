<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Container\Facades;

use Valkyrja\Container\Container as Contract;
use Valkyrja\Container\Facades\Container as Facade;
use Valkyrja\Tests\Unit\Facade\FacadeTestCase;

/**
 * Test the Container Facade service.
 *
 * @author Melech Mizrachi
 */
class ContainerTest extends FacadeTestCase
{
    /** @var class-string */
    protected static string $contract = Contract::class;
    /** @var class-string<Facade> */
    protected static string $facade = Facade::class;

    /**
     * @inheritDoc
     */
    public static function methods(): array
    {
        return [
            ['has'],
            ['bind'],
            ['bindAlias'],
            ['bindSingleton'],
            ['setClosure'],
            ['setSingleton'],
            ['isAlias'],
            ['isClosure'],
            ['isService'],
            ['isSingleton'],
            ['get'],
            ['getClosure'],
            ['getService'],
            ['getSingleton'],
        ];
    }
}
