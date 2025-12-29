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

use Valkyrja\Application\Provider\ComponentProvider;

/**
 * Class ComponentClass.
 *
 * @author Melech Mizrachi
 */
final class ComponentClass
{
    public const string APPLICATION  = ComponentProvider::class;
    public const string API          = \Valkyrja\Api\Provider\ComponentProvider::class;
    public const string ATTRIBUTE    = \Valkyrja\Attribute\Provider\ComponentProvider::class;
    public const string AUTH         = \Valkyrja\Auth\Provider\ComponentProvider::class;
    public const string BROADCAST    = \Valkyrja\Broadcast\Provider\ComponentProvider::class;
    public const string CACHE        = \Valkyrja\Cache\Provider\ComponentProvider::class;
    public const string CLI          = \Valkyrja\Cli\ComponentProvider::class;
    public const string CONTAINER    = \Valkyrja\Container\Provider\ComponentProvider::class;
    public const string CRYPT        = \Valkyrja\Crypt\Provider\ComponentProvider::class;
    public const string DISPATCHER   = \Valkyrja\Dispatch\Provider\ComponentProvider::class;
    public const string EVENT        = \Valkyrja\Event\Provider\ComponentProvider::class;
    public const string FILESYSTEM   = \Valkyrja\Filesystem\Provider\ComponentProvider::class;
    public const string HTTP         = \Valkyrja\Http\ComponentProvider::class;
    public const string JWT          = \Valkyrja\Jwt\Provider\ComponentProvider::class;
    public const string LOG          = \Valkyrja\Log\Provider\ComponentProvider::class;
    public const string MAIL         = \Valkyrja\Mail\Provider\ComponentProvider::class;
    public const string NOTIFICATION = \Valkyrja\Notification\Provider\ComponentProvider::class;
    public const string ORM          = \Valkyrja\Orm\Provider\ComponentProvider::class;
    public const string REFLECTION   = \Valkyrja\Reflection\Provider\ComponentProvider::class;
    public const string SESSION      = \Valkyrja\Session\Provider\ComponentProvider::class;
    public const string SMS          = \Valkyrja\Sms\Provider\ComponentProvider::class;
    public const string VIEW         = \Valkyrja\View\Provider\ComponentProvider::class;
}
