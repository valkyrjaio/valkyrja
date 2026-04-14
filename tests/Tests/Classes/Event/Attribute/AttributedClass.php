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

namespace Valkyrja\Tests\Classes\Event\Attribute;

use Valkyrja\Event\Attribute\Listener;
use Valkyrja\Event\Attribute\ListenerHandler;
use Valkyrja\Tests\Unit\Event\Collector\AttributesCollectorTest;

/**
 * Class with attributes used for unit testing.
 */
// Testing invalid attributes that have no method attached to them since this class has no constructor
#[Listener(AttributesCollectorTest::VALUE1, 'AttributedClassValue1')]
#[Listener(AttributesCollectorTest::VALUE2, 'AttributedClassValue2')]
final class AttributedClass
{
    #[Listener(AttributesCollectorTest::VALUE1, 'AttributedClass::staticMethodValue1')]
    #[Listener(AttributesCollectorTest::VALUE2, 'AttributedClass::staticMethodValue2')]
    #[ListenerHandler([self::class, 'handler'])]
    public static function staticMethod(): string
    {
        return 'Static Method';
    }

    public static function handler(): string
    {
        return 'Handler';
    }

    public static function handler2(): string
    {
        return 'Handler2';
    }

    #[Listener(AttributesCollectorTest::VALUE1, 'AttributedClass->methodValue1', [self::class, 'handler2'])]
    #[Listener(AttributesCollectorTest::VALUE2, 'AttributedClass->methodValue2', [self::class, 'handler2'])]
    public function method(): string
    {
        return 'Method';
    }
}
