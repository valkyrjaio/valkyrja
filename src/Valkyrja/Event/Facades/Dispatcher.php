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

use Valkyrja\Event\Dispatcher as Contract;
use Valkyrja\Event\Listener;
use Valkyrja\Facade\ContainerFacade;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 *
 * @method static object      dispatch(object $event)
 * @method static object|null dispatchIfHasListeners(object $event)
 * @method static object      dispatchById(string $eventId, array $arguments = [])
 * @method static object|null dispatchByIdIfHasListeners(string $eventId, array $arguments = [])
 * @method static object      dispatchListeners(object $event, Listener ...$listeners)
 * @method static object      dispatchListenersGivenId(string $eventId, Listener ...$listeners)
 * @method static object      dispatchListener(object $event, Listener $listener)
 * @method static object      dispatchListenerGivenId(string $eventId, Listener $listener)
 */
class Dispatcher extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
