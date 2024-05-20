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

namespace Valkyrja\Tests\Unit\Type\Vlid;

use Exception;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Vlid\Support\VlidV1 as Helper;
use Valkyrja\Type\Vlid\VlidV1 as Id;

use function json_encode;

class VlidV1Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConstruct(): void
    {
        $vlid = new Id();

        self::assertTrue(Helper::isValid($vlid->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        $vlid = new Id(Helper::generateLowerCase());

        self::assertTrue(Helper::isValid($vlid->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $id = Id::fromValue(Helper::generate());

        self::assertTrue(Helper::isValid($id->asValue()));
    }

    public function testAsFlatValue(): void
    {
        $id = new Id();

        self::assertTrue(Helper::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = Helper::generate();
        $type     = new Id($value);
        $newValue = Helper::generate();

        $modified = $type->modify(static fn (string $subject): string => $newValue);

        self::assertNotSame($type->asValue(), $modified->asValue());
        // Original should be unmodified
        self::assertSame($value, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    /**
     * @throws Exception
     */
    public function testIntJsonSerialize(): void
    {
        $value = Helper::generate();
        $type  = new Id($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
