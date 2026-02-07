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
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Uuid;

use function json_encode;

final class UuidTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testFromInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Uuid::fromValue(1);
    }

    /**
     * @throws Exception
     */
    public function testUuidV1(): void
    {
        $id = new Uuid(UuidFactory::v1());

        self::assertTrue(UuidFactory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testUuidV3(): void
    {
        $id = new Uuid(UuidFactory::v3(UuidFactory::v1(), 'test'));

        self::assertTrue(UuidFactory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testUuidV4(): void
    {
        $id = new Uuid(UuidFactory::v4());

        self::assertTrue(UuidFactory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testUuidV5(): void
    {
        $id = new Uuid(UuidFactory::v5(UuidFactory::v1(), 'test'));

        self::assertTrue(UuidFactory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testUuidV6(): void
    {
        $id = new Uuid(UuidFactory::v6());

        self::assertTrue(UuidFactory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testAsFlatValue(): void
    {
        $id = new Uuid(UuidFactory::v1());

        self::assertTrue(UuidFactory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = UuidFactory::v1();
        $type     = new Uuid($value);
        $newValue = UuidFactory::v1();

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
        $value = UuidFactory::v1();
        $type  = new Uuid($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
