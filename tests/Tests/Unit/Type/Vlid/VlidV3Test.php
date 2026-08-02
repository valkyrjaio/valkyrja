<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Vlid;

use Exception;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Vlid\Factory\VlidV3Factory;
use Valkyrja\Type\Vlid\VlidV3;

use function json_encode;

final class VlidV3Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConstruct(): void
    {
        $vlid = new VlidV3();

        self::assertTrue(VlidV3Factory::isValid($vlid->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        $vlid = new VlidV3(VlidV3Factory::generateLowerCase());

        self::assertTrue(VlidV3Factory::isValid($vlid->asValue()));
    }

    /**
     * @throws Exception
     */
    public function testFromValue(): void
    {
        $id = VlidV3::fromValue(VlidV3Factory::generate());

        self::assertTrue(VlidV3Factory::isValid($id->asValue()));
    }

    public function testAsFlatValue(): void
    {
        $id = new VlidV3();

        self::assertTrue(VlidV3Factory::isValid($id->asFlatValue()));
    }

    /**
     * @throws Exception
     */
    public function testModify(): void
    {
        $value    = VlidV3Factory::generate();
        $type     = new VlidV3($value);
        $newValue = VlidV3Factory::generate();

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
        $value = VlidV3Factory::generate();
        $type  = new VlidV3($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
