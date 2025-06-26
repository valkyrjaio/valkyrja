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

namespace Valkyrja\Container\Constant;

use Valkyrja\Api\Provider\ServiceProvider as ApiServiceProvider;
use Valkyrja\Attribute\Provider\ServiceProvider as AttributesServiceProvider;
use Valkyrja\Auth\Provider\ServiceProvider as AuthServiceProvider;
use Valkyrja\Broadcast\Provider\ServiceProvider as BroadcastServiceProvider;
use Valkyrja\Cache\Provider\ServiceProvider as CacheServiceProvider;
use Valkyrja\Cli\Interaction\Provider\ServiceProvider as CliInteractionServiceProvider;
use Valkyrja\Cli\Middleware\Provider\ServiceProvider as CliMiddlewareServiceProvider;
use Valkyrja\Cli\Routing\Provider\ServiceProvider as CliRoutingServiceProvider;
use Valkyrja\Cli\Server\Provider\ServiceProvider as CliServerServiceProvider;
use Valkyrja\Container\Provider\ServiceProvider as ContainerServiceProvider;
use Valkyrja\Crypt\Provider\ServiceProvider as CryptServiceProvider;
use Valkyrja\Dispatcher\Provider\ServiceProvider as DispatcherServiceProvider;
use Valkyrja\Event\Provider\ServiceProvider as EventServiceProvider;
use Valkyrja\Filesystem\Provider\ServiceProvider as FilesystemServiceProvider;
use Valkyrja\Http\Client\Provider\ServiceProvider as ClientServiceProvider;
use Valkyrja\Http\Message\Provider\ServiceProvider as HttpMessageServiceProvider;
use Valkyrja\Http\Middleware\Provider\ServiceProvider as HttpMiddlewareServiceProvider;
use Valkyrja\Http\Routing\Provider\ServiceProvider as HttpRoutingServiceProvider;
use Valkyrja\Http\Server\Provider\ServiceProvider as HttpServerServiceProvider;
use Valkyrja\Jwt\Provider\ServiceProvider as JWTServiceProvider;
use Valkyrja\Log\Provider\ServiceProvider as LogServiceProvider;
use Valkyrja\Mail\Provider\ServiceProvider as MailServiceProvider;
use Valkyrja\Notification\Provider\ServiceProvider as NotificationServiceProvider;
use Valkyrja\Orm\Provider\ServiceProvider as ORMServiceProvider;
use Valkyrja\Reflection\Provider\ServiceProvider as ReflectionServiceProvider;
use Valkyrja\Session\Provider\ServiceProvider as SessionServiceProvider;
use Valkyrja\Sms\Provider\ServiceProvider as SMSServiceProvider;
use Valkyrja\View\Provider\ServiceProvider as ViewServiceProvider;

/**
 * Constant Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider
{
    public const string API             = ApiServiceProvider::class;
    public const string ATTRIBUTES      = AttributesServiceProvider::class;
    public const string AUTH            = AuthServiceProvider::class;
    public const string BROADCAST       = BroadcastServiceProvider::class;
    public const string CACHE           = CacheServiceProvider::class;
    public const string CLI_MESSAGE     = CliInteractionServiceProvider::class;
    public const string CLI_MIDDLEWARE  = CliMiddlewareServiceProvider::class;
    public const string CLI_ROUTING     = CliRoutingServiceProvider::class;
    public const string CLI_SERVER      = CliServerServiceProvider::class;
    public const string CLIENT          = ClientServiceProvider::class;
    public const string CONTAINER       = ContainerServiceProvider::class;
    public const string CRYPT           = CryptServiceProvider::class;
    public const string DISPATCHER      = DispatcherServiceProvider::class;
    public const string EVENT           = EventServiceProvider::class;
    public const string FILESYSTEM      = FilesystemServiceProvider::class;
    public const string HTTP_MESSAGE    = HttpMessageServiceProvider::class;
    public const string HTTP_MIDDLEWARE = HttpMiddlewareServiceProvider::class;
    public const string HTTP_ROUTING    = HttpRoutingServiceProvider::class;
    public const string HTTP_SERVER     = HttpServerServiceProvider::class;
    public const string JWT             = JWTServiceProvider::class;
    public const string LOG             = LogServiceProvider::class;
    public const string MAIL            = MailServiceProvider::class;
    public const string NOTIFICATION    = NotificationServiceProvider::class;
    public const string ORM             = ORMServiceProvider::class;
    public const string REFLECTION      = ReflectionServiceProvider::class;
    public const string SESSION         = SessionServiceProvider::class;
    public const string SMS             = SMSServiceProvider::class;
    public const string VIEW            = ViewServiceProvider::class;
}
