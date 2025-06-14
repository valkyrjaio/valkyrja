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

use Valkyrja\Annotation\Provider\ServiceProvider as AnnotationServiceProvider;
use Valkyrja\Api\Provider\ServiceProvider as ApiServiceProvider;
use Valkyrja\Attribute\Provider\ServiceProvider as AttributesServiceProvider;
use Valkyrja\Auth\Provider\ServiceProvider as AuthServiceProvider;
use Valkyrja\Broadcast\Provider\ServiceProvider as BroadcastServiceProvider;
use Valkyrja\Cache\Provider\ServiceProvider as CacheServiceProvider;
use Valkyrja\Client\Provider\ServiceProvider as ClientServiceProvider;
use Valkyrja\Console\Provider\CommandServiceProvider as ConsoleCommandServiceProvider;
use Valkyrja\Console\Provider\ServiceProvider as ConsoleServiceProvider;
use Valkyrja\Container\Provider\ServiceProvider as ContainerServiceProvider;
use Valkyrja\Crypt\Provider\ServiceProvider as CryptServiceProvider;
use Valkyrja\Dispatcher\Provider\ServiceProvider as DispatcherServiceProvider;
use Valkyrja\Event\Provider\ServiceProvider as EventServiceProvider;
use Valkyrja\Filesystem\Provider\ServiceProvider as FilesystemServiceProvider;
use Valkyrja\Http\Message\Provider\ServiceProvider as HttpMessageServiceProvider;
use Valkyrja\Http\Middleware\Provider\ServiceProvider as HttpMiddlewareServiceProvider;
use Valkyrja\Http\Routing\Provider\CommandServiceProvider as HttpRoutingCommandServiceProvider;
use Valkyrja\Http\Routing\Provider\ServiceProvider as HttpRoutingServiceProvider;
use Valkyrja\Http\Server\Provider\ServiceProvider as HttpKernelServiceProvider;
use Valkyrja\Jwt\Provider\ServiceProvider as JWTServiceProvider;
use Valkyrja\Log\Provider\ServiceProvider as LogServiceProvider;
use Valkyrja\Mail\Provider\ServiceProvider as MailServiceProvider;
use Valkyrja\Notification\Provider\ServiceProvider as NotificationServiceProvider;
use Valkyrja\Orm\Provider\ServiceProvider as ORMServiceProvider;
use Valkyrja\Path\Provider\ServiceProvider as PathServiceProvider;
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
    public const ANNOTATION            = AnnotationServiceProvider::class;
    public const API                   = ApiServiceProvider::class;
    public const ATTRIBUTES            = AttributesServiceProvider::class;
    public const AUTH                  = AuthServiceProvider::class;
    public const BROADCAST             = BroadcastServiceProvider::class;
    public const CACHE                 = CacheServiceProvider::class;
    public const CLIENT                = ClientServiceProvider::class;
    public const CONSOLE               = ConsoleServiceProvider::class;
    public const CONSOLE_COMMANDS      = ConsoleCommandServiceProvider::class;
    public const CONTAINER             = ContainerServiceProvider::class;
    public const CRYPT                 = CryptServiceProvider::class;
    public const DISPATCHER            = DispatcherServiceProvider::class;
    public const EVENT                 = EventServiceProvider::class;
    public const FILESYSTEM            = FilesystemServiceProvider::class;
    public const HTTP_MESSAGE          = HttpMessageServiceProvider::class;
    public const HTTP_MIDDLEWARE       = HttpMiddlewareServiceProvider::class;
    public const HTTP_ROUTING          = HttpRoutingServiceProvider::class;
    public const HTTP_ROUTING_COMMANDS = HttpRoutingCommandServiceProvider::class;
    public const HTTP_SERVER           = HttpKernelServiceProvider::class;
    public const JWT                   = JWTServiceProvider::class;
    public const LOG                   = LogServiceProvider::class;
    public const MAIL                  = MailServiceProvider::class;
    public const NOTIFICATION          = NotificationServiceProvider::class;
    public const ORM                   = ORMServiceProvider::class;
    public const PATH                  = PathServiceProvider::class;
    public const REFLECTION            = ReflectionServiceProvider::class;
    public const SESSION               = SessionServiceProvider::class;
    public const SMS                   = SMSServiceProvider::class;
    public const VIEW                  = ViewServiceProvider::class;
}
