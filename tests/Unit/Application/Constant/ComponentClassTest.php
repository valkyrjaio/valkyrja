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

namespace Valkyrja\Tests\Unit\Application\Constant;

use Valkyrja\Application\Component;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ComponentClass constant.
 *
 * @author Melech Mizrachi
 */
class ComponentClassTest extends TestCase
{
    public function testValues(): void
    {
        self::assertSame(Component::class, ComponentClass::APPLICATION);
        self::assertSame(\Valkyrja\Api\Component::class, ComponentClass::API);
        self::assertSame(\Valkyrja\Attribute\Component::class, ComponentClass::ATTRIBUTE);
        self::assertSame(\Valkyrja\Auth\Component::class, ComponentClass::AUTH);
        self::assertSame(\Valkyrja\Broadcast\Component::class, ComponentClass::BROADCAST);
        self::assertSame(\Valkyrja\Cache\Component::class, ComponentClass::CACHE);
        self::assertSame(\Valkyrja\Cli\Component::class, ComponentClass::CLI);
        self::assertSame(\Valkyrja\Container\Component::class, ComponentClass::CONTAINER);
        self::assertSame(\Valkyrja\Crypt\Component::class, ComponentClass::CRYPT);
        self::assertSame(\Valkyrja\Dispatch\Component::class, ComponentClass::DISPATCHER);
        self::assertSame(\Valkyrja\Event\Component::class, ComponentClass::EVENT);
        self::assertSame(\Valkyrja\Filesystem\Component::class, ComponentClass::FILESYSTEM);
        self::assertSame(\Valkyrja\Http\Component::class, ComponentClass::HTTP);
        self::assertSame(\Valkyrja\Jwt\Component::class, ComponentClass::JWT);
        self::assertSame(\Valkyrja\Log\Component::class, ComponentClass::LOG);
        self::assertSame(\Valkyrja\Mail\Component::class, ComponentClass::MAIL);
        self::assertSame(\Valkyrja\Notification\Component::class, ComponentClass::NOTIFICATION);
        self::assertSame(\Valkyrja\Orm\Component::class, ComponentClass::ORM);
        self::assertSame(\Valkyrja\Reflection\Component::class, ComponentClass::REFLECTION);
        self::assertSame(\Valkyrja\Session\Component::class, ComponentClass::SESSION);
        self::assertSame(\Valkyrja\Sms\Component::class, ComponentClass::SMS);
        self::assertSame(\Valkyrja\View\Component::class, ComponentClass::VIEW);
    }
}
