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
#[Attribute(AttributesTest::VALUE1)]
#[Attribute(AttributesTest::VALUE2)]
#[AttributeChild(AttributesTest::VALUE3, AttributesTest::THREE)]
class AttributedClass
{
    #[Attribute(AttributesTest::VALUE4)]
    #[Attribute(AttributesTest::VALUE5)]
    #[AttributeChild(AttributesTest::VALUE6, AttributesTest::SIX)]
    public const CONST = 'Const';

    #[Attribute(AttributesTest::VALUE7)]
    #[Attribute(AttributesTest::VALUE8)]
    #[AttributeChild(AttributesTest::VALUE9, AttributesTest::NINE)]
    protected const PROTECTED_CONST = 'Protected Const';

    #[Attribute(AttributesTest::VALUE10)]
    #[Attribute(AttributesTest::VALUE11)]
    #[AttributeChild(AttributesTest::VALUE12, AttributesTest::TWELVE)]
    public static string $staticProperty = 'Static Property';

    #[Attribute(AttributesTest::VALUE13)]
    #[Attribute(AttributesTest::VALUE14)]
    #[AttributeChild(AttributesTest::VALUE15, AttributesTest::FIFTEEN)]
    public string $property = 'Property';

    #[Attribute(AttributesTest::VALUE16)]
    #[Attribute(AttributesTest::VALUE17)]
    #[AttributeChild(AttributesTest::VALUE18, AttributesTest::EIGHTEEN)]
    public static function staticMethod(): string
    {
        return 'Static Method';
    }

    #[Attribute(AttributesTest::VALUE19)]
    #[Attribute(AttributesTest::VALUE20)]
    #[AttributeChild(AttributesTest::VALUE21, AttributesTest::TWENTY_ONE)]
    public function method(): string
    {
        return 'Method';
    }

    #[Attribute(AttributesTest::VALUE19)]
    #[Attribute(AttributesTest::VALUE20)]
    #[AttributeChild(AttributesTest::VALUE21, AttributesTest::TWENTY_ONE)]
    public function methodWithParameter(
        #[Attribute(AttributesTest::VALUE19)]
        #[Attribute(AttributesTest::VALUE20)]
        #[AttributeChild(AttributesTest::VALUE21, AttributesTest::TWENTY_ONE)]
        string $parameter = 'fire'
    ): string {
        return 'Method with Parameter';
    }
}
