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

use Valkyrja\Api\Provider\ComponentProvider as ApiComponentProvider;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Provider\ComponentProvider as ApplicationComponentProvider;
use Valkyrja\Attribute\Provider\ComponentProvider as AttributeComponentProvider;
use Valkyrja\Auth\Provider\ComponentProvider as AuthComponentProvider;
use Valkyrja\Broadcast\Provider\ComponentProvider as BroadcastComponentProvider;
use Valkyrja\Cache\Provider\ComponentProvider as CacheComponentProvider;
use Valkyrja\Cli\Interaction\Provider\ComponentProvider as CliInteractionComponentProvider;
use Valkyrja\Cli\Middleware\Provider\ComponentProvider as CliMiddlewareComponentProvider;
use Valkyrja\Cli\Routing\Provider\ComponentProvider as CliRoutingComponentProvider;
use Valkyrja\Cli\Server\Provider\ComponentProvider as CliServerComponentProvider;
use Valkyrja\Container\Provider\ComponentProvider as ContainerComponentProvider;
use Valkyrja\Crypt\Provider\ComponentProvider as CryptComponentProvider;
use Valkyrja\Dispatch\Provider\ComponentProvider as DispatchComponentProvider;
use Valkyrja\Event\Provider\ComponentProvider as EventComponentProvider;
use Valkyrja\Filesystem\Provider\ComponentProvider as FilesystemComponentProvider;
use Valkyrja\Http\Client\Provider\ComponentProvider as HttpClientComponentProvider;
use Valkyrja\Http\Message\Provider\ComponentProvider as HttpMessageComponentProvider;
use Valkyrja\Http\Middleware\Provider\ComponentProvider as HttpMiddlewareComponentProvider;
use Valkyrja\Http\Routing\Provider\ComponentProvider as HttpRoutingComponentProvider;
use Valkyrja\Http\Server\Provider\ComponentProvider as HttpServerComponentProvider;
use Valkyrja\Jwt\Provider\ComponentProvider as JwtComponentProvider;
use Valkyrja\Log\Provider\ComponentProvider as LogComponentProvider;
use Valkyrja\Mail\Provider\ComponentProvider as MailComponentProvider;
use Valkyrja\Notification\Provider\ComponentProvider as NotificationComponentProvider;
use Valkyrja\Orm\Provider\ComponentProvider as OrmComponentProvider;
use Valkyrja\Reflection\Provider\ComponentProvider as ReflectionComponentProvider;
use Valkyrja\Session\Provider\ComponentProvider as SessionComponentProvider;
use Valkyrja\Sms\Provider\ComponentProvider as SmsComponentProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Provider\ComponentProvider as ViewComponentProvider;

/**
 * Test the ComponentClass constant.
 */
class ComponentClassTest extends TestCase
{
    public function testValues(): void
    {
        self::assertSame(ApplicationComponentProvider::class, ComponentClass::APPLICATION);
        self::assertSame(ApiComponentProvider::class, ComponentClass::API);
        self::assertSame(AttributeComponentProvider::class, ComponentClass::ATTRIBUTE);
        self::assertSame(AuthComponentProvider::class, ComponentClass::AUTH);
        self::assertSame(BroadcastComponentProvider::class, ComponentClass::BROADCAST);
        self::assertSame(CacheComponentProvider::class, ComponentClass::CACHE);
        self::assertSame(CliInteractionComponentProvider::class, ComponentClass::CLI_INTERACTION);
        self::assertSame(CliMiddlewareComponentProvider::class, ComponentClass::CLI_MIDDLEWARE);
        self::assertSame(CliRoutingComponentProvider::class, ComponentClass::CLI_ROUTING);
        self::assertSame(CliServerComponentProvider::class, ComponentClass::CLI_SERVER);
        self::assertSame(ContainerComponentProvider::class, ComponentClass::CONTAINER);
        self::assertSame(CryptComponentProvider::class, ComponentClass::CRYPT);
        self::assertSame(DispatchComponentProvider::class, ComponentClass::DISPATCHER);
        self::assertSame(EventComponentProvider::class, ComponentClass::EVENT);
        self::assertSame(FilesystemComponentProvider::class, ComponentClass::FILESYSTEM);
        self::assertSame(HttpClientComponentProvider::class, ComponentClass::HTTP_CLIENT);
        self::assertSame(HttpMessageComponentProvider::class, ComponentClass::HTTP_MESSAGE);
        self::assertSame(HttpMiddlewareComponentProvider::class, ComponentClass::HTTP_MIDDLEWARE);
        self::assertSame(HttpRoutingComponentProvider::class, ComponentClass::HTTP_ROUTING);
        self::assertSame(HttpServerComponentProvider::class, ComponentClass::HTTP_SERVER);
        self::assertSame(JwtComponentProvider::class, ComponentClass::JWT);
        self::assertSame(LogComponentProvider::class, ComponentClass::LOG);
        self::assertSame(MailComponentProvider::class, ComponentClass::MAIL);
        self::assertSame(NotificationComponentProvider::class, ComponentClass::NOTIFICATION);
        self::assertSame(OrmComponentProvider::class, ComponentClass::ORM);
        self::assertSame(ReflectionComponentProvider::class, ComponentClass::REFLECTION);
        self::assertSame(SessionComponentProvider::class, ComponentClass::SESSION);
        self::assertSame(SmsComponentProvider::class, ComponentClass::SMS);
        self::assertSame(ViewComponentProvider::class, ComponentClass::VIEW);
    }
}
