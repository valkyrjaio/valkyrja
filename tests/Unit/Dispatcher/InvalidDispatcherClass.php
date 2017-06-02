<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Dispatcher;

use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Events\Events;

/**
 * Invalid dispatcher class to test with.
 *
 * @author Melech Mizrachi
 */
class InvalidDispatcherClass
{
    /**
     * The container.
     *
     * @var \Valkyrja\Contracts\Container\Container
     */
    protected $container;

    /**
     * The events.
     *
     * @var \Valkyrja\Contracts\Events\Events
     */
    protected $events;

    /**
     * InvalidDispatcherClass constructor.
     *
     * @param \Valkyrja\Contracts\Container\Container $container The container
     * @param \Valkyrja\Contracts\Events\Events       $events    The events
     */
    public function __construct(Container $container, Events $events)
    {
        $this->container = $container;
        $this->events    = $events;
    }
}
