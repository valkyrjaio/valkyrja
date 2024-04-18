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
use Valkyrja\Type\Support\Uuid;
use Valkyrja\Type\Support\UuidV5 as Helper;
use Valkyrja\Type\Types\UuidV5 as Id;

use function json_encode;

class UuidV5Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConstruct(): void
    {
        $id = new Id(Helper::generate(Uuid::v1(), 'test'));

        self::assertTrue(Helper::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $id = Id::fromValue(Helper::generate(Uuid::v1(), 'test'));

        self::assertTrue(Helper::isValid($id->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testAsFlatValue(): void
    {
        $id = new Id(Helper::generate(Uuid::v1(), 'test'));

        self::assertTrue(Helper::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = Helper::generate(Uuid::v1(), 'test');
        $type     = new Id($value);
        $newValue = Helper::generate(Uuid::v1(), 'test');

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
        $value = Helper::generate(Uuid::v1(), 'test');
        $type  = new Id($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
