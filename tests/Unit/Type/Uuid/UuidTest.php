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

namespace Valkyrja\Tests\Unit\Type\Uuid;

use Exception;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Support\Uuid as Helper;
use Valkyrja\Type\Types\Uuid as Id;

use function json_encode;

class UuidTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testUuidV1(): void
    {
        $id = new Id(Helper::v1());

        self::assertTrue(Helper::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testUuidV3(): void
    {
        $id = new Id(Helper::v3(Helper::v1(), 'test'));

        self::assertTrue(Helper::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testUuidV4(): void
    {
        $id = new Id(Helper::v4());

        self::assertTrue(Helper::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testUuidV5(): void
    {
        $id = new Id(Helper::v5(Helper::v1(), 'test'));

        self::assertTrue(Helper::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testUuidV6(): void
    {
        $id = new Id(Helper::v6());

        self::assertTrue(Helper::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testAsFlatValue(): void
    {
        $id = new Id(Helper::v1());

        self::assertTrue(Helper::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = Helper::v1();
        $type     = new Id($value);
        $newValue = Helper::v1();

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
        $value = Helper::v1();
        $type  = new Id($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
