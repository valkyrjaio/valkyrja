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
use Valkyrja\Tests\Unit\Event\Collector\AttributesCollectorTest;

/**
 * Class with attributes used for unit testing.
 *
 * @author Melech Mizrachi
 */
// Testing valid class attributes that will be attached to the constructor
#[Listener(AttributesCollectorTest::VALUE1, 'Attributed2ClassValue1')]
#[Listener(AttributesCollectorTest::VALUE2, 'Attributed2ClassValue2')]
class Attributed2Class
{
    public function __construct()
    {
    }

    #[Listener(AttributesCollectorTest::VALUE1, 'Attributed2Class::staticMethodValue1')]
    #[Listener(AttributesCollectorTest::VALUE2, 'Attributed2Class::staticMethodValue2')]
    public static function staticMethod(): string
    {
        return 'Static Method';
    }

    #[Listener(AttributesCollectorTest::VALUE1, 'Attributed2Class->methodValue1')]
    #[Listener(AttributesCollectorTest::VALUE2, 'Attributed2Class->methodValue1')]
    public function method(): string
    {
        return 'Method';
    }
}
