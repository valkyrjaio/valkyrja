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

namespace Valkyrja\Event\Dispatchers;

use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Event\Event;
use Valkyrja\Event\Events as EventsContract;
use Valkyrja\Event\Listener;
use Valkyrja\Event\Models\Listener as ListenerModel;

use function get_class;
use function is_array;

/**
 * Class Events.
 *
 * @author Melech Mizrachi
 */
class Events implements EventsContract
{
    /**
     * The event listeners.
     *
     * @var Listener[][]
     */
    protected static array $events = [];

    /**
     * The dispatcher.
     *
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Events constructor.
     *
     * @param Dispatcher $dispatcher The dispatcher
     * @param array      $config     The config
     */
    public function __construct(Dispatcher $dispatcher, array $config)
    {
        $this->dispatcher = $dispatcher;
        $this->config     = $config;
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

        $this->dispatcher->verifyDispatch($listener);

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
            ? $this->ensureListeners(self::$events[$event])
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

        // TODO: if ($arguments !== null && $event is class) $arguments = new $event(...$arguments)

        // Iterate through all the event's listeners
        foreach ($this->getListeners($event) as $listener) {
            // Attempt to dispatch the event listener using any one of the callable options
            $dispatch = $this->dispatcher->dispatch($this->ensureListener($listener), $arguments);

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
        return $this->ensureEventListeners(self::$events);
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

    /**
     * Ensure events are arrays of listeners.
     *
     * @param array $eventsArray
     *
     * @return array
     */
    protected function ensureEventListeners(array $eventsArray): array
    {
        $events = [];

        foreach ($eventsArray as $key => $method) {
            $eventsArray[$key] = $this->ensureListeners($method);
        }

        return $events;
    }

    /**
     * Ensure an array is an array of listeners.
     *
     * @param array $listenersArray The listeners array
     *
     * @return array
     */
    protected function ensureListeners(array $listenersArray): array
    {
        $listeners = [];

        foreach ($listenersArray as $key => $listener) {
            $listeners[$key] = $this->ensureListener($listener);
        }

        return $listeners;
    }

    /**
     * Ensure a listener, or null, is returned.
     *
     * @param Listener|array $listener The listener
     *
     * @return Listener
     */
    protected function ensureListener($listener): Listener
    {
        if (is_array($listener)) {
            return ListenerModel::fromArray($listener);
        }

        return $listener;
    }
}
