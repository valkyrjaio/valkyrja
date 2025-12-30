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

use Valkyrja\Api\Provider\ComponentProvider as ApiComponentProvider;
use Valkyrja\Application\Provider\ComponentProvider as ApplicationComponentProvider;
use Valkyrja\Attribute\Provider\ComponentProvider as AttributeComponentProvider;
use Valkyrja\Auth\Provider\ComponentProvider as AuthComponentProvider;
use Valkyrja\Broadcast\Provider\ComponentProvider as BroadcastComponentProvider;
use Valkyrja\Cache\Provider\ComponentProvider as CacheComponentProvider;
use Valkyrja\Cli\Provider\ComponentProvider as CliComponentProvider;
use Valkyrja\Container\Provider\ComponentProvider as ContainerComponentProvider;
use Valkyrja\Crypt\Provider\ComponentProvider as CryptComponentProvider;
use Valkyrja\Dispatch\Provider\ComponentProvider as DispatchComponentProvider;
use Valkyrja\Event\Provider\ComponentProvider as EventComponentProvider;
use Valkyrja\Filesystem\Provider\ComponentProvider as FilesystemComponentProvider;
use Valkyrja\Http\Provider\ComponentProvider as HttpComponentProvider;
use Valkyrja\Jwt\Provider\ComponentProvider as JwtComponentProvider;
use Valkyrja\Log\Provider\ComponentProvider as LogComponentProvider;
use Valkyrja\Mail\Provider\ComponentProvider as MailComponentProvider;
use Valkyrja\Notification\Provider\ComponentProvider as NotificationComponentProvider;
use Valkyrja\Orm\Provider\ComponentProvider as OrmComponentProvider;
use Valkyrja\Reflection\Provider\ComponentProvider as ReflectionComponentProvider;
use Valkyrja\Session\Provider\ComponentProvider as SessionComponentProvider;
use Valkyrja\Sms\Provider\ComponentProvider as SmsComponentProvider;
use Valkyrja\View\Provider\ComponentProvider as ViewComponentProvider;

/**
 * Class ComponentClass.
 *
 * @author Melech Mizrachi
 */
final class ComponentClass
{
    public const string APPLICATION  = ApplicationComponentProvider::class;
    public const string API          = ApiComponentProvider::class;
    public const string ATTRIBUTE    = AttributeComponentProvider::class;
    public const string AUTH         = AuthComponentProvider::class;
    public const string BROADCAST    = BroadcastComponentProvider::class;
    public const string CACHE        = CacheComponentProvider::class;
    public const string CLI          = CliComponentProvider::class;
    public const string CONTAINER    = ContainerComponentProvider::class;
    public const string CRYPT        = CryptComponentProvider::class;
    public const string DISPATCHER   = DispatchComponentProvider::class;
    public const string EVENT        = EventComponentProvider::class;
    public const string FILESYSTEM   = FilesystemComponentProvider::class;
    public const string HTTP         = HttpComponentProvider::class;
    public const string JWT          = JwtComponentProvider::class;
    public const string LOG          = LogComponentProvider::class;
    public const string MAIL         = MailComponentProvider::class;
    public const string NOTIFICATION = NotificationComponentProvider::class;
    public const string ORM          = OrmComponentProvider::class;
    public const string REFLECTION   = ReflectionComponentProvider::class;
    public const string SESSION      = SessionComponentProvider::class;
    public const string SMS          = SmsComponentProvider::class;
    public const string VIEW         = ViewComponentProvider::class;
}
