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

namespace Valkyrja\Tests\Unit\Dispatcher\Facade;

use Valkyrja\Dispatcher\Contract\Dispatcher as Contract;
use Valkyrja\Dispatcher\Facade\Dispatcher as Facade;
use Valkyrja\Tests\Unit\Facade\FacadeTestCase;

/**
 * Test the Dispatch Facade service.
 *
 * @author Melech Mizrachi
 */
class DispatcherTest extends FacadeTestCase
{
    /** @inheritDoc */
    protected static string $contract = Contract::class;
    /** @inheritDoc */
    protected static string $facade = Facade::class;

    /**
     * @inheritDoc
     */
    public static function methods(): array
    {
        return [
            ['dispatch'],
        ];
    }
}
