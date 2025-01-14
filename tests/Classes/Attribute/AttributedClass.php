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

namespace Valkyrja\Tests\Classes\Attribute;

use Valkyrja\Tests\Unit\Attribute\AttributesTest;

/**
 * Class with attributes used for unit testing.
 *
 * @author Melech Mizrachi
 */
#[AttributeClass(AttributesTest::VALUE1)]
#[AttributeClass(AttributesTest::VALUE2)]
#[AttributeClassChildClass(AttributesTest::VALUE3, AttributesTest::THREE)]
class AttributedClass
{
    #[AttributeClass(AttributesTest::VALUE4)]
    #[AttributeClass(AttributesTest::VALUE5)]
    #[AttributeClassChildClass(AttributesTest::VALUE6, AttributesTest::SIX)]
    public const CONST = 'Const';

    #[AttributeClass(AttributesTest::VALUE7)]
    #[AttributeClass(AttributesTest::VALUE8)]
    #[AttributeClassChildClass(AttributesTest::VALUE9, AttributesTest::NINE)]
    protected const PROTECTED_CONST = 'Protected Const';

    #[AttributeClass(AttributesTest::VALUE10)]
    #[AttributeClass(AttributesTest::VALUE11)]
    #[AttributeClassChildClass(AttributesTest::VALUE12, AttributesTest::TWELVE)]
    public static string $staticProperty = 'Static Property';

    #[AttributeClass(AttributesTest::VALUE13)]
    #[AttributeClass(AttributesTest::VALUE14)]
    #[AttributeClassChildClass(AttributesTest::VALUE15, AttributesTest::FIFTEEN)]
    public string $property = 'Property';

    #[AttributeClass(AttributesTest::VALUE16)]
    #[AttributeClass(AttributesTest::VALUE17)]
    #[AttributeClassChildClass(AttributesTest::VALUE18, AttributesTest::EIGHTEEN)]
    public static function staticMethod(): string
    {
        return 'Static Method';
    }

    #[AttributeClass(AttributesTest::VALUE19)]
    #[AttributeClass(AttributesTest::VALUE20)]
    #[AttributeClassChildClass(AttributesTest::VALUE21, AttributesTest::TWENTY_ONE)]
    public function method(): string
    {
        return 'Method';
    }

    #[AttributeClass(AttributesTest::VALUE19)]
    #[AttributeClass(AttributesTest::VALUE20)]
    #[AttributeClassChildClass(AttributesTest::VALUE21, AttributesTest::TWENTY_ONE)]
    public function methodWithParameter(
        #[AttributeClass(AttributesTest::VALUE19)]
        #[AttributeClass(AttributesTest::VALUE20)]
        #[AttributeClassChildClass(AttributesTest::VALUE21, AttributesTest::TWENTY_ONE)]
        string $parameter = 'fire'
    ): string {
        return 'Method with Parameter';
    }
}
