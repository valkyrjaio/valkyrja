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
use Valkyrja\Type\Vlid\Factory\VlidV2Factory;
use Valkyrja\Type\Vlid\VlidV2;

use function json_encode;

final class VlidV2Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConstruct(): void
    {
        $vlid = new VlidV2();

        self::assertTrue(VlidV2Factory::isValid($vlid->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        $vlid = new VlidV2(VlidV2Factory::generateLowerCase());

        self::assertTrue(VlidV2Factory::isValid($vlid->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $id = VlidV2::fromValue(VlidV2Factory::generate());

        self::assertTrue(VlidV2Factory::isValid($id->asValue()));
    }

    public function testAsFlatValue(): void
    {
        $id = new VlidV2();

        self::assertTrue(VlidV2Factory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = VlidV2Factory::generate();
        $type     = new VlidV2($value);
        $newValue = VlidV2Factory::generate();

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
        $value = VlidV2Factory::generate();
        $type  = new VlidV2($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
