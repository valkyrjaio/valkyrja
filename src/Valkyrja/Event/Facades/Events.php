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

namespace Valkyrja\Event\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Event\Event;
use Valkyrja\Event\Listener;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Events.
 *
 * @author Melech Mizrachi
 *
 * @method static void listen(string $event, Listener $listener)
 * @method static void listenMany(Listener $listener, string ...$events)
 * @method static bool hasListener(string $event, string $listenerId)
 * @method static void removeListener(string $event, string $listenerId)
 * @method static Listener[] getListeners(string $event)
 * @method static bool hasListeners(string $event)
 * @method static void add(string $event)
 * @method static bool has(string $event)
 * @method static void remove(string $event)
 * @method static mixed[] trigger(string $event, array $arguments = null)
 * @method static mixed[] event(Event $event)
 * @method static array all()
 * @method static void setEvents(array $events)
 */
class Events extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->events();
    }
}
