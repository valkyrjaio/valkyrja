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
 * Class Event.
 *
 * @author Melech Mizrachi
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

    /**
     * Get an listener from properties.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Events\Listener
     */
    public static function getListener(array $properties): self
    {
        $dispatch = new self();

        $dispatch
            ->setEvent($properties['event'] ?? null)
            ->setId($properties['id'] ?? null)
            ->setClass($properties['class'] ?? null)
            ->setProperty($properties['property'] ?? null)
            ->setMethod($properties['method'] ?? null)
            ->setStatic($properties['static'] ?? null)
            ->setFunction($properties['function'] ?? null)
            ->setClosure($properties['closure'] ?? null)
            ->setMatches($properties['matches'] ?? null)
            ->setArguments($properties['arguments'] ?? null)
            ->setDependencies($properties['dependencies'] ?? null);

        return $dispatch;
    }

    /**
     * Set the state of the listener.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Events\Listener
     */
    public static function __set_state(array $properties)
    {
        return static::getListener($properties);
    }
}
