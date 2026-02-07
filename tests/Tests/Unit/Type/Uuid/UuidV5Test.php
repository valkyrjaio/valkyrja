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
use Valkyrja\Type\Uuid\Factory\UuidV5Factory;
use Valkyrja\Type\Uuid\UuidV5;

use function json_encode;

final class UuidV5Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConstruct(): void
    {
        $id = new UuidV5(UuidV5Factory::generate(UuidFactory::v1(), 'test'));

        self::assertTrue(UuidV5Factory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $id = UuidV5::fromValue(UuidV5Factory::generate(UuidFactory::v1(), 'test'));

        self::assertTrue(UuidV5Factory::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        UuidV5::fromValue(1);
    }

    /**
     * @throws Exception
     */
    public function testAsFlatValue(): void
    {
        $id = new UuidV5(UuidV5Factory::generate(UuidFactory::v1(), 'test'));

        self::assertTrue(UuidV5Factory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = UuidV5Factory::generate(UuidFactory::v1(), 'test');
        $type     = new UuidV5($value);
        $newValue = UuidV5Factory::generate(UuidFactory::v1(), 'test');

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
        $value = UuidV5Factory::generate(UuidFactory::v1(), 'test');
        $type  = new UuidV5($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
