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

use Valkyrja\Tests\Unit\Attribute\Collector\CollectorTest;

/**
 * Class with attributes used for unit testing.
 */
#[AttributeClass(CollectorTest::VALUE1)]
#[AttributeClass(CollectorTest::VALUE2)]
#[AttributeClassChildClass(CollectorTest::VALUE3, CollectorTest::THREE)]
class AttributedClass
{
    #[AttributeClass(CollectorTest::VALUE4)]
    #[AttributeClass(CollectorTest::VALUE5)]
    #[AttributeClassChildClass(CollectorTest::VALUE6, CollectorTest::SIX)]
    public const string CONST = 'Const';

    #[AttributeClass(CollectorTest::VALUE7)]
    #[AttributeClass(CollectorTest::VALUE8)]
    #[AttributeClassChildClass(CollectorTest::VALUE9, CollectorTest::NINE)]
    protected const string PROTECTED_CONST = 'Protected Const';

    #[AttributeClass(CollectorTest::VALUE10)]
    #[AttributeClass(CollectorTest::VALUE11)]
    #[AttributeClassChildClass(CollectorTest::VALUE12, CollectorTest::TWELVE)]
    public static string $staticProperty = 'Static Property';

    #[AttributeClass(CollectorTest::VALUE13)]
    #[AttributeClass(CollectorTest::VALUE14)]
    #[AttributeClassChildClass(CollectorTest::VALUE15, CollectorTest::FIFTEEN)]
    public string $property = 'Property';

    #[AttributeClass(CollectorTest::VALUE16)]
    #[AttributeClass(CollectorTest::VALUE17)]
    #[AttributeClassChildClass(CollectorTest::VALUE18, CollectorTest::EIGHTEEN)]
    public static function staticMethod(): string
    {
        return 'Static Method';
    }

    #[AttributeClass(CollectorTest::VALUE19)]
    #[AttributeClass(CollectorTest::VALUE20)]
    #[AttributeClassChildClass(CollectorTest::VALUE21, CollectorTest::TWENTY_ONE)]
    public function method(): string
    {
        return 'Method';
    }

    #[AttributeClass(CollectorTest::VALUE19)]
    #[AttributeClass(CollectorTest::VALUE20)]
    #[AttributeClassChildClass(CollectorTest::VALUE21, CollectorTest::TWENTY_ONE)]
    public function methodWithParameter(
        #[AttributeClass(CollectorTest::VALUE19)]
        #[AttributeClass(CollectorTest::VALUE20)]
        #[AttributeClassChildClass(CollectorTest::VALUE21, CollectorTest::TWENTY_ONE)]
        string $parameter = 'fire'
    ): string {
        return 'Method with Parameter';
    }
}
