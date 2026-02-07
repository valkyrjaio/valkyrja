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
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\Vlid\Factory\VlidFactory;
use Valkyrja\Type\Vlid\Vlid;

use function json_encode;

class VlidTest extends TestCase
{
    public function testConstruct(): void
    {
        $vlid = new Vlid();

        self::assertTrue(VlidFactory::isValid($vlid->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $vlid = Vlid::fromValue(VlidFactory::v1());

        self::assertTrue(VlidFactory::isValid($vlid->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Vlid::fromValue(1);
    }

    public function testAsFlatValue(): void
    {
        $id = new Vlid();

        self::assertTrue(VlidFactory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = VlidFactory::generate();
        $type     = new Vlid($value);
        $newValue = VlidFactory::generate();

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
        $value = VlidFactory::generate();
        $type  = new Vlid($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
