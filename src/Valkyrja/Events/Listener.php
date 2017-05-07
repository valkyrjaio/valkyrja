<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Events;

use Valkyrja\Contracts\Annotations\Annotation;
use Valkyrja\Dispatcher\Dispatch;

/**
 * Class Event
 *
 * @package Valkyrja\Events
 *
 * @author  Melech Mizrachi
 */
class Listener extends Dispatch implements Annotation
{
    /**
     * The event to listen to.
     *
     * @var string
     */
    protected $event;

    /**
     * Get the event.
     *
     * @return string
     */
    public function getEvent():? string
    {
        return $this->event;
    }

    /**
     * Set the event.
     *
     * @param string $event The event
     *
     * @return \Valkyrja\Events\Listener
     */
    public function setEvent(string $event = null): self
    {
        $this->event = $event;

        return $this;
    }
}
