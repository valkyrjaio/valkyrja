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

use Valkyrja\Dispatcher\Dispatch;

/**
 * Class Event.
 *
 * @author Melech Mizrachi
 */
class Listener extends Dispatch
{
    /**
     * The event to listen to.
     *
     * @var string|null
     */
    protected ?string $event;

    /**
     * Get the event.
     *
     * @return string
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * Set the event.
     *
     * @param string $event The event
     *
     * @return Listener
     */
    public function setEvent(string $event = null): self
    {
        $this->event = $event;

        return $this;
    }
}
