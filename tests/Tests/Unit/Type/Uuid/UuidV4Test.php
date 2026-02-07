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
use Valkyrja\Type\Uuid\Factory\UuidV4Factory;
use Valkyrja\Type\Uuid\UuidV4;

use function json_encode;

class UuidV4Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConstruct(): void
    {
        $id = new UuidV4();

        self::assertTrue(UuidV4Factory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $id = UuidV4::fromValue(UuidV4Factory::generate());

        self::assertTrue(UuidV4Factory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        UuidV4::fromValue(1);
    }

    public function testAsFlatValue(): void
    {
        $id = new UuidV4();

        self::assertTrue(UuidV4Factory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = UuidV4Factory::generate();
        $type     = new UuidV4($value);
        $newValue = UuidV4Factory::generate();

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
        $value = UuidV4Factory::generate();
        $type  = new UuidV4($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
