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

use Valkyrja\Application\Component;

/**
 * Class ComponentClass.
 *
 * @author Melech Mizrachi
 */
final class ComponentClass
{
    public const string APPLICATION  = Component::class;
    public const string API          = \Valkyrja\Api\Component::class;
    public const string ATTRIBUTE    = \Valkyrja\Attribute\Component::class;
    public const string AUTH         = \Valkyrja\Auth\Component::class;
    public const string BROADCAST    = \Valkyrja\Broadcast\Component::class;
    public const string CACHE        = \Valkyrja\Cache\Component::class;
    public const string CLI          = \Valkyrja\Cli\Component::class;
    public const string CONTAINER    = \Valkyrja\Container\Component::class;
    public const string CRYPT        = \Valkyrja\Crypt\Component::class;
    public const string DISPATCHER   = \Valkyrja\Dispatch\Component::class;
    public const string EVENT        = \Valkyrja\Event\Component::class;
    public const string FILESYSTEM   = \Valkyrja\Filesystem\Component::class;
    public const string HTTP         = \Valkyrja\Http\Component::class;
    public const string JWT          = \Valkyrja\Jwt\Component::class;
    public const string LOG          = \Valkyrja\Log\Component::class;
    public const string MAIL         = \Valkyrja\Mail\Component::class;
    public const string NOTIFICATION = \Valkyrja\Notification\Component::class;
    public const string ORM          = \Valkyrja\Orm\Component::class;
    public const string REFLECTION   = \Valkyrja\Reflection\Component::class;
    public const string SESSION      = \Valkyrja\Session\Component::class;
    public const string SMS          = \Valkyrja\Sms\Component::class;
    public const string VIEW         = \Valkyrja\View\Component::class;
}
