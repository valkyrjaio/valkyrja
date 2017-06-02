<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Container;

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Events\Events;

/**
 * Invalid container class to test with.
 *
 * @author Melech Mizrachi
 */
class InvalidContainerClass
{
    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * The events.
     *
     * @var \Valkyrja\Contracts\Events\Events
     */
    protected $events;

    /**
     * InvalidContainerClass constructor.
     *
     * @param \Valkyrja\Contracts\Application   $application The application
     * @param \Valkyrja\Contracts\Events\Events $events      The events
     */
    public function __construct(Application $application, Events $events)
    {
        $this->app    = $application;
        $this->events = $events;
    }
}
