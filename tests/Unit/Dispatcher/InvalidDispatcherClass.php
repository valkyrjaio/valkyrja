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

use Valkyrja\Container\Container;
use Valkyrja\Events\Events;

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
     * @var \Valkyrja\Container\Container
     */
    protected $container;

    /**
     * The events.
     *
     * @var \Valkyrja\Events\Events
     */
    protected $events;

    /**
     * InvalidDispatcherClass constructor.
     *
     * @param \Valkyrja\Container\Container $container The container
     * @param \Valkyrja\Events\Events       $events    The events
     */
    public function __construct(Container $container, Events $events)
    {
        $this->container = $container;
        $this->events    = $events;
    }
}
