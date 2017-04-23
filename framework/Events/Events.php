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

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Events\Events as EventsContract;
use Valkyrja\Dispatcher\Traits\Dispatcher;

/**
 * Class Events
 *
 * @package Valkyrja\Events
 *
 * @author  Melech Mizrachi
 */
class Events implements EventsContract
{
    use Dispatcher;

    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * The event listeners.
     *
     * @var array
     */
    protected $events = [];

    /**
     * Events constructor.
     *
     * @param \Valkyrja\Contracts\Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Add an event listener.
     *
     * @param string                    $event    The event
     * @param \Valkyrja\Events\Listener $listener The event listener
     *
     * @return void
     *
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     */
    public function addListener(string $event, Listener $listener): void
    {
        $this->add($event);

        $this->verifyDispatch($listener);

        $this->events[$event][$listener->getName()] = $listener;
    }

    /**
     * Determine whether an event has a specified listener.
     *
     * @param string                    $event    The event
     * @param \Valkyrja\Events\Listener $listener The event listener
     *
     * @return bool
     */
    public function hasListener(string $event, Listener $listener): bool
    {
        return $this->has($event) && isset($this->events[$event][$listener]);
    }

    /**
     * Remove an event listener.
     *
     * @param string                    $event    The event
     * @param \Valkyrja\Events\Listener $listener The event listener
     *
     * @return void
     */
    public function removeListener(string $event, Listener $listener): void
    {
        if ($this->hasListener($event, $listener)) {
            unset($this->events[$event][$listener]);
        }
    }

    /**
     * Get the event's listeners.
     *
     * @param string $event The event
     *
     * @return \Valkyrja\Events\Listener[]
     */
    public function getListeners(string $event): array
    {
        $this->add($event);

        return $this->events[$event];
    }

    /**
     * Determine whether an event has listeners.
     *
     * @param string $event The event
     *
     * @return bool
     */
    public function hasListeners(string $event): bool
    {
        return $this->has($event) && ! empty($this->events[$event]);
    }

    /**
     * Add a new event.
     *
     * @param string $event The event
     *
     * @return void
     */
    public function add(string $event): void
    {
        if (! $this->has($event)) {
            $this->events[$event] = [];
        }
    }

    /**
     * Determine whether an event exists.
     *
     * @param string $event The event
     *
     * @return bool
     */
    public function has(string $event): bool
    {
        return isset($this->events[$event]);
    }

    /**
     * Remove an event.
     *
     * @param string $event The event
     *
     * @return void
     */
    public function remove(string $event): void
    {
        if ($this->has($event)) {
            unset($this->events[$event]);
        }
    }

    /**
     * Trigger an event.
     *
     * @param string $event     The event
     * @param array  $arguments The arguments
     *
     * @return array
     */
    public function trigger(string $event, array $arguments = []): array
    {
        $this->add($event);

        // The responses
        $responses = [];

        // Iterate through all the event's listeners
        foreach ($this->getListeners($event) as $listener) {
            $listenerArguments = $this->getListenerArguments($listener, $arguments);
            // Attempt to dispatch the event listener using any one of the callable options
            $dispatch = $this->dispatchCallable($listener, $listenerArguments);

            if (null !== $dispatch) {
                $responses[] = $dispatch;
            }
        }

        return $responses;
    }

    /**
     * Get an event listeners arguments.
     *
     * @param \Valkyrja\Events\Listener $listener The event listener
     * @param array                     $arguments The arguments
     *
     * @return array
     */
    protected function getListenerArguments(Listener $listener, array $arguments = []): array
    {
        // If the listener has dependencies
        if ($listener->getDependencies()) {
            // Set the listener arguments to a new blank array
            $listenerArguments = $this->getDependencies($listener);

            // Iterate through the arguments
            foreach ($arguments as $argument) {
                // Append the argument to the arguments list
                $listenerArguments[] = $argument;
            }

            return $listenerArguments;
        }

        return $arguments;
    }

    /**
     * Get all events.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->events;
    }

    /**
     * Set the events.
     *
     * @param array $events The events
     *
     * @return void
     */
    public function set(array $events): void
    {
        $this->events = $events;
    }
}
