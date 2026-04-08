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

use Valkyrja\Api\Provider\ApiComponentProvider;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Provider\ApplicationComponentProvider;
use Valkyrja\Attribute\Provider\AttributeComponentProvider;
use Valkyrja\Auth\Provider\AuthComponentProvider;
use Valkyrja\Broadcast\Provider\BroadcastComponentProvider;
use Valkyrja\Cache\Provider\CacheComponentProvider;
use Valkyrja\Cli\Interaction\Provider\CliInteractionComponentProvider;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareComponentProvider;
use Valkyrja\Cli\Routing\Provider\CliRoutingComponentProvider;
use Valkyrja\Cli\Server\Provider\CliServerComponentProvider;
use Valkyrja\Container\Provider\ContainerComponentProvider;
use Valkyrja\Crypt\Provider\CryptComponentProvider;
use Valkyrja\Dispatch\Provider\DispatchComponentProvider;
use Valkyrja\Event\Provider\EventComponentProvider;
use Valkyrja\Filesystem\Provider\FilesystemComponentProvider;
use Valkyrja\Http\Client\Provider\HttpClientComponentProvider;
use Valkyrja\Http\Message\Provider\HttpMessageComponentProvider;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingComponentProvider;
use Valkyrja\Http\Server\Provider\HttpServerComponentProvider;
use Valkyrja\Jwt\Provider\JwtComponentProvider;
use Valkyrja\Log\Provider\LogComponentProvider;
use Valkyrja\Mail\Provider\MailComponentProvider;
use Valkyrja\Orm\Provider\OrmComponentProvider;
use Valkyrja\Reflection\Provider\ReflectionComponentProvider;
use Valkyrja\Session\Provider\SessionComponentProvider;
use Valkyrja\Sms\Provider\SmsComponentProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Provider\ViewComponentProvider;

/**
 * Test the ComponentClass constant.
 */
final class ComponentClassTest extends TestCase
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
        self::assertSame(OrmComponentProvider::class, ComponentClass::ORM);
        self::assertSame(ReflectionComponentProvider::class, ComponentClass::REFLECTION);
        self::assertSame(SessionComponentProvider::class, ComponentClass::SESSION);
        self::assertSame(SmsComponentProvider::class, ComponentClass::SMS);
        self::assertSame(ViewComponentProvider::class, ComponentClass::VIEW);
    }
}
