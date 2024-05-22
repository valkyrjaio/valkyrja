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
use Valkyrja\Console\Provider\ServiceProvider as ConsoleServiceProvider;
use Valkyrja\Container\Provider\ServiceProvider as ContainerServiceProvider;
use Valkyrja\Crypt\Provider\ServiceProvider as CryptServiceProvider;
use Valkyrja\Dispatcher\Provider\ServiceProvider as DispatcherServiceProvider;
use Valkyrja\Event\Provider\ServiceProvider as EventServiceProvider;
use Valkyrja\Filesystem\Provider\ServiceProvider as FilesystemServiceProvider;
use Valkyrja\Http\Providers\ServiceProvider as HttpServiceProvider;
use Valkyrja\HttpKernel\Provider\ServiceProvider as HttpKernelServiceProvider;
use Valkyrja\Jwt\Provider\ServiceProvider as JWTServiceProvider;
use Valkyrja\Log\Provider\ServiceProvider as LogServiceProvider;
use Valkyrja\Mail\Provider\ServiceProvider as MailServiceProvider;
use Valkyrja\Notification\Providers\ServiceProvider as NotificationServiceProvider;
use Valkyrja\Orm\Providers\ServiceProvider as ORMServiceProvider;
use Valkyrja\Path\Provider\ServiceProvider as PathServiceProvider;
use Valkyrja\Reflection\Provider\ServiceProvider as ReflectionServiceProvider;
use Valkyrja\Routing\Providers\ServiceProvider as RoutingServiceProvider;
use Valkyrja\Session\Providers\ServiceProvider as SessionServiceProvider;
use Valkyrja\Sms\Providers\ServiceProvider as SMSServiceProvider;
use Valkyrja\Validation\Providers\ServiceProvider as ValidationServiceProvider;
use Valkyrja\View\Providers\ServiceProvider as ViewServiceProvider;

/**
 * Constant Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider
{
    public const ANNOTATION   = AnnotationServiceProvider::class;
    public const API          = ApiServiceProvider::class;
    public const ATTRIBUTES   = AttributesServiceProvider::class;
    public const AUTH         = AuthServiceProvider::class;
    public const BROADCAST    = BroadcastServiceProvider::class;
    public const CACHE        = CacheServiceProvider::class;
    public const CLIENT       = ClientServiceProvider::class;
    public const CONSOLE      = ConsoleServiceProvider::class;
    public const CONTAINER    = ContainerServiceProvider::class;
    public const CRYPT        = CryptServiceProvider::class;
    public const DISPATCHER   = DispatcherServiceProvider::class;
    public const EVENT        = EventServiceProvider::class;
    public const FILESYSTEM   = FilesystemServiceProvider::class;
    public const HTTP         = HttpServiceProvider::class;
    public const HTTP_KERNEL  = HttpKernelServiceProvider::class;
    public const JWT          = JWTServiceProvider::class;
    public const LOG          = LogServiceProvider::class;
    public const MAIL         = MailServiceProvider::class;
    public const NOTIFICATION = NotificationServiceProvider::class;
    public const ORM          = ORMServiceProvider::class;
    public const PATH         = PathServiceProvider::class;
    public const REFLECTION   = ReflectionServiceProvider::class;
    public const ROUTING      = RoutingServiceProvider::class;
    public const SESSION      = SessionServiceProvider::class;
    public const SMS          = SMSServiceProvider::class;
    public const VALIDATION   = ValidationServiceProvider::class;
    public const VIEW         = ViewServiceProvider::class;
}
