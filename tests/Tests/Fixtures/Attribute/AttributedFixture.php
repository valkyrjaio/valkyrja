<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Attribute;

use Valkyrja\Tests\Unit\Attribute\Collector\CollectorTest;

/**
 * Class with attributes used for unit testing.
 */
#[AttributeFixture(CollectorTest::VALUE1)]
#[AttributeFixture(CollectorTest::VALUE2)]
#[AttributeClassChildFixture(CollectorTest::VALUE3, CollectorTest::THREE)]
final class AttributedFixture
{
    #[AttributeFixture(CollectorTest::VALUE4)]
    #[AttributeFixture(CollectorTest::VALUE5)]
    #[AttributeClassChildFixture(CollectorTest::VALUE6, CollectorTest::SIX)]
    public const string CONST = 'Const';

    #[AttributeFixture(CollectorTest::VALUE7)]
    #[AttributeFixture(CollectorTest::VALUE8)]
    #[AttributeClassChildFixture(CollectorTest::VALUE9, CollectorTest::NINE)]
    private const string PROTECTED_CONST = 'Protected Const';

    #[AttributeFixture(CollectorTest::VALUE10)]
    #[AttributeFixture(CollectorTest::VALUE11)]
    #[AttributeClassChildFixture(CollectorTest::VALUE12, CollectorTest::TWELVE)]
    public static string $staticProperty = 'Static Property';

    #[AttributeFixture(CollectorTest::VALUE13)]
    #[AttributeFixture(CollectorTest::VALUE14)]
    #[AttributeClassChildFixture(CollectorTest::VALUE15, CollectorTest::FIFTEEN)]
    public string $property = 'Property';

    #[AttributeFixture(CollectorTest::VALUE16)]
    #[AttributeFixture(CollectorTest::VALUE17)]
    #[AttributeClassChildFixture(CollectorTest::VALUE18, CollectorTest::EIGHTEEN)]
    public static function staticMethod(): string
    {
        return 'Static Method';
    }

    #[AttributeFixture(CollectorTest::VALUE19)]
    #[AttributeFixture(CollectorTest::VALUE20)]
    #[AttributeClassChildFixture(CollectorTest::VALUE21, CollectorTest::TWENTY_ONE)]
    public function method(): string
    {
        return 'Method';
    }

    #[AttributeFixture(CollectorTest::VALUE19)]
    #[AttributeFixture(CollectorTest::VALUE20)]
    #[AttributeClassChildFixture(CollectorTest::VALUE21, CollectorTest::TWENTY_ONE)]
    public function methodWithParameter(
        #[AttributeFixture(CollectorTest::VALUE19)]
        #[AttributeFixture(CollectorTest::VALUE20)]
        #[AttributeClassChildFixture(CollectorTest::VALUE21, CollectorTest::TWENTY_ONE)]
        string $parameter = 'fire'
    ): string {
        return 'Method with Parameter';
    }
}
