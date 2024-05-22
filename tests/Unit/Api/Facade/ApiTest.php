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

namespace Valkyrja\Tests\Unit\Api\Facade;

use Valkyrja\Api\Contract\Api as Contract;
use Valkyrja\Api\Facade\Api as Facade;
use Valkyrja\Tests\Unit\Facade\FacadeTestCase;

/**
 * Test the Api Facade service.
 *
 * @author Melech Mizrachi
 */
class ApiTest extends FacadeTestCase
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
            ['jsonFromException'],
            ['jsonResponseFromException'],
            ['jsonFromObject'],
            ['jsonResponseFromObject'],
            ['jsonFromObjects'],
            ['jsonResponseFromObjects'],
            ['jsonFromArray'],
            ['jsonResponseFromArray'],
            ['jsonFromEntity'],
            ['jsonResponseFromEntity'],
            ['jsonFromEntities'],
            ['jsonResponseFromEntities'],
        ];
    }
}
