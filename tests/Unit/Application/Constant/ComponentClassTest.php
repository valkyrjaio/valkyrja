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

use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Provider\ComponentProvider;
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
        self::assertSame(ComponentProvider::class, ComponentClass::APPLICATION);
        self::assertSame(\Valkyrja\Api\Provider\ComponentProvider::class, ComponentClass::API);
        self::assertSame(\Valkyrja\Attribute\Provider\ComponentProvider::class, ComponentClass::ATTRIBUTE);
        self::assertSame(\Valkyrja\Auth\Provider\ComponentProvider::class, ComponentClass::AUTH);
        self::assertSame(\Valkyrja\Broadcast\Provider\ComponentProvider::class, ComponentClass::BROADCAST);
        self::assertSame(\Valkyrja\Cache\Provider\ComponentProvider::class, ComponentClass::CACHE);
        self::assertSame(\Valkyrja\Cli\Provider\ComponentProvider::class, ComponentClass::CLI);
        self::assertSame(\Valkyrja\Container\Provider\ComponentProvider::class, ComponentClass::CONTAINER);
        self::assertSame(\Valkyrja\Crypt\Provider\ComponentProvider::class, ComponentClass::CRYPT);
        self::assertSame(\Valkyrja\Dispatch\Provider\ComponentProvider::class, ComponentClass::DISPATCHER);
        self::assertSame(\Valkyrja\Event\Provider\ComponentProvider::class, ComponentClass::EVENT);
        self::assertSame(\Valkyrja\Filesystem\Provider\ComponentProvider::class, ComponentClass::FILESYSTEM);
        self::assertSame(\Valkyrja\Http\Client\Provider\ComponentProvider::class, ComponentClass::HTTP_CLIENT);
        self::assertSame(\Valkyrja\Http\Message\Provider\ComponentProvider::class, ComponentClass::HTTP_MESSAGE);
        self::assertSame(\Valkyrja\Http\Middleware\Provider\ComponentProvider::class, ComponentClass::HTTP_MIDDLEWARE);
        self::assertSame(\Valkyrja\Http\Routing\Provider\ComponentProvider::class, ComponentClass::HTTP_ROUTING);
        self::assertSame(\Valkyrja\Http\Server\Provider\ComponentProvider::class, ComponentClass::HTTP_SERVER);
        self::assertSame(\Valkyrja\Jwt\Provider\ComponentProvider::class, ComponentClass::JWT);
        self::assertSame(\Valkyrja\Log\Provider\ComponentProvider::class, ComponentClass::LOG);
        self::assertSame(\Valkyrja\Mail\Provider\ComponentProvider::class, ComponentClass::MAIL);
        self::assertSame(\Valkyrja\Notification\Provider\ComponentProvider::class, ComponentClass::NOTIFICATION);
        self::assertSame(\Valkyrja\Orm\Provider\ComponentProvider::class, ComponentClass::ORM);
        self::assertSame(\Valkyrja\Reflection\Provider\ComponentProvider::class, ComponentClass::REFLECTION);
        self::assertSame(\Valkyrja\Session\Provider\ComponentProvider::class, ComponentClass::SESSION);
        self::assertSame(\Valkyrja\Sms\Provider\ComponentProvider::class, ComponentClass::SMS);
        self::assertSame(\Valkyrja\View\Provider\ComponentProvider::class, ComponentClass::VIEW);
    }
}
