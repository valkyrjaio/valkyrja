<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Event\Dispatchers;

use Valkyrja\Application\Application;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Event\Cacheables\CacheableEvents;
use Valkyrja\Event\Event;
use Valkyrja\Event\Events as EventsContract;
use Valkyrja\Event\Listener;

use function get_class;

/**
 * Class Events.
 *
 * @author Melech Mizrachi
 */
class Events implements EventsContract
{
    use CacheableEvents;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Events constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Add an event listener.
     *
     * @param string   $event    The event
     * @param Listener $listener The event listener
     *
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws InvalidClosureException
     *
     * @return void
     */
    public function listen(string $event, Listener $listener): void
    {
        $this->add($event);

        $this->app->dispatcher()->verifyDispatch($listener);

        // If this listener has an id
        if (null !== $listener->getId()) {
            // Use it when setting to allow removal
            // or checking if it exists later
            self::$events[$event][$listener->getId()] = $listener;
        } else {
            // Otherwise set the listener normally
            self::$events[$event][] = $listener;
        }
    }

    /**
     * Add a listener to many events.
     *
     * @param Listener $listener  The listener
     * @param string   ...$events The events
     *
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws InvalidClosureException
     *
     * @return void
     */
    public function listenMany(Listener $listener, string ...$events): void
    {
        // Iterate through the events
        foreach ($events as $event) {
            // Set a new listener for the event
            $this->listen($event, $listener);
        }
    }

    /**
     * Determine whether an event has a specified listener.
     *
     * @param string $event      The event
     * @param string $listenerId The event listener
     *
     * @return bool
     */
    public function hasListener(string $event, string $listenerId): bool
    {
        return $this->has($event) && isset(self::$events[$event][$listenerId]);
    }

    /**
     * Remove an event listener.
     *
     * @param string $event      The event
     * @param string $listenerId The event listener
     *
     * @return void
     */
    public function removeListener(string $event, string $listenerId): void
    {
        // If the listener exists
        if ($this->hasListener($event, $listenerId)) {
            // Unset it
            unset(self::$events[$event][$listenerId]);
        }
    }

    /**
     * Get the event's listeners.
     *
     * @param string $event The event
     *
     * @return Listener[]
     */
    public function getListeners(string $event): array
    {
        return $this->has($event)
            ? self::$events[$event]
            : [];
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
        return $this->has($event) && ! empty(self::$events[$event]);
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
            self::$events[$event] = [];
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
        return isset(self::$events[$event]);
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
            unset(self::$events[$event]);
        }
    }

    /**
     * Trigger an event.
     *
     * @param string $event     The event
     * @param array  $arguments [optional] The arguments
     *
     * @return mixed[]
     */
    public function trigger(string $event, array $arguments = null): array
    {
        // The responses
        $responses = [];

        if (! $this->has($event) || ! $this->hasListeners($event)) {
            return $responses;
        }

        // Iterate through all the event's listeners
        foreach ($this->getListeners($event) as $listener) {
            // Attempt to dispatch the event listener using any one of the
            // callable options
            $dispatch = $this->app->dispatcher()->dispatch($listener, $arguments);

            if (null !== $dispatch) {
                $responses[] = $dispatch;
            }
        }

        return $responses;
    }

    /**
     * Trigger an event.
     *
     * @param Event $event The event
     *
     * @return mixed[]
     */
    public function event(Event $event): array
    {
        return $this->trigger(get_class($event), [$event]);
    }

    /**
     * Get all events.
     *
     * @return Listener[][]
     */
    public function all(): array
    {
        return self::$events;
    }

    /**
     * Set the events.
     *
     * @param Listener[][] $events The events
     *
     * @return void
     */
    public function setEvents(array $events): void
    {
        self::$events = $events;
    }
}
