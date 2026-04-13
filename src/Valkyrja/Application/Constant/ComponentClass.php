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

namespace Valkyrja\Application\Constant;

use Valkyrja\Api\Provider\ApiComponentProvider;
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
use Valkyrja\Http\Routing\Provider\HttpRoutingCliComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingComponentProvider;
use Valkyrja\Http\Server\Provider\HttpServerComponentProvider;
use Valkyrja\Jwt\Provider\JwtComponentProvider;
use Valkyrja\Log\Provider\LogComponentProvider;
use Valkyrja\Mail\Provider\MailComponentProvider;
use Valkyrja\Orm\Provider\OrmComponentProvider;
use Valkyrja\Reflection\Provider\ReflectionComponentProvider;
use Valkyrja\Session\Provider\SessionComponentProvider;
use Valkyrja\Sms\Provider\SmsComponentProvider;
use Valkyrja\View\Provider\ViewComponentProvider;

final class ComponentClass
{
    public const string API              = ApiComponentProvider::class;
    public const string ATTRIBUTE        = AttributeComponentProvider::class;
    public const string AUTH             = AuthComponentProvider::class;
    public const string BROADCAST        = BroadcastComponentProvider::class;
    public const string CACHE            = CacheComponentProvider::class;
    public const string CLI_INTERACTION  = CliInteractionComponentProvider::class;
    public const string CLI_MIDDLEWARE   = CliMiddlewareComponentProvider::class;
    public const string CLI_ROUTING      = CliRoutingComponentProvider::class;
    public const string CLI_SERVER       = CliServerComponentProvider::class;
    public const string CONTAINER        = ContainerComponentProvider::class;
    public const string CRYPT            = CryptComponentProvider::class;
    public const string DISPATCHER       = DispatchComponentProvider::class;
    public const string EVENT            = EventComponentProvider::class;
    public const string FILESYSTEM       = FilesystemComponentProvider::class;
    public const string HTTP_CLIENT      = HttpClientComponentProvider::class;
    public const string HTTP_MESSAGE     = HttpMessageComponentProvider::class;
    public const string HTTP_MIDDLEWARE  = HttpMiddlewareComponentProvider::class;
    public const string HTTP_ROUTING     = HttpRoutingComponentProvider::class;
    public const string HTTP_ROUTING_CLI = HttpRoutingCliComponentProvider::class;
    public const string HTTP_SERVER      = HttpServerComponentProvider::class;
    public const string JWT              = JwtComponentProvider::class;
    public const string LOG              = LogComponentProvider::class;
    public const string MAIL             = MailComponentProvider::class;
    public const string ORM              = OrmComponentProvider::class;
    public const string REFLECTION       = ReflectionComponentProvider::class;
    public const string SESSION          = SessionComponentProvider::class;
    public const string SMS              = SmsComponentProvider::class;
    public const string VIEW             = ViewComponentProvider::class;
}
