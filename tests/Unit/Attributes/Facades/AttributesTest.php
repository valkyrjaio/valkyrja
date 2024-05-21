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

namespace Valkyrja\Tests\Unit\Attributes\Facades;

use Valkyrja\Attribute\Contract\Attributes as Contract;
use Valkyrja\Attribute\Facade\Attributes as Facade;
use Valkyrja\Tests\Unit\Facade\FacadeTestCase;

/**
 * Test the Attributes Facade service.
 *
 * @author Melech Mizrachi
 */
class AttributesTest extends FacadeTestCase
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
            ['forClass'],
            ['forClassMembers'],
            ['forClassAndMembers'],
            ['forConstant'],
            ['forConstants'],
            ['forProperty'],
            ['forProperties'],
            ['forMethod'],
            ['forMethods'],
            ['forFunction'],
            ['forClosure'],
        ];
    }
}
