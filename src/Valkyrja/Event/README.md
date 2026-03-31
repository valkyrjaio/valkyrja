# Event Dispatching

## Introduction

Valkyrja's event system is built
on [PSR-14](https://www.php-fig.org/psr/psr-14/), the PHP standard for event
dispatching. Any PSR-14 compliant event object works with the dispatcher out of
the box, and the dispatcher itself can be used anywhere a
`Psr\EventDispatcher\EventDispatcherInterface` is expected.

On top of PSR-14, Valkyrja adds argument passing to events, collection of
listener return values, attribute-based listener registration, and stoppable
event support — all integrated naturally with the container and the deferred
loading model.

## Core Concepts

An **event** is a plain PHP class that represents something that has occurred in
your application. It carries whatever data is relevant to that occurrence.
Events are plain objects — there is no required base class.

A **listener** is a class or method that responds to a specific event when it is
dispatched. Listeners receive the event object and perform whatever action is
appropriate.

The **event dispatcher** —
`Valkyrja\Event\Dispatcher\Contract\DispatcherContract` — is the service that
connects events to their listeners and invokes each listener when an event is
fired.

## Dispatching Events

The dispatcher provides six methods, giving you precise control over dispatch
behaviour.

**`dispatch(object $event): object`** — The standard PSR-14 method. Pass a
constructed event object; the dispatcher invokes all registered listeners in
order and returns the event.

```php
$dispatcher->dispatch(new UserRegistered($user));
```

**`dispatchIfHasListeners(object $event): object`** — Same as `dispatch()`, but
only runs if at least one listener is registered for the event. Useful for
optional hooks where firing with no listeners would be wasteful.

```php
$dispatcher->dispatchIfHasListeners(new UserRegistered($user));
```

**`dispatchById(string $eventId, array $arguments = []): object`** — Dispatch by
class name. The dispatcher instantiates the event and, if the event implements
`ArgumentsCapableEventContract`, calls `setArguments()` with the provided array
before invoking listeners.

```php
$dispatcher->dispatchById(UserRegistered::class, [$user]);
```

**`dispatchByIdIfHasListeners(string $eventId, array $arguments = []): object`
** — Same as `dispatchById()`, but only runs if listeners are registered.

```php
$dispatcher->dispatchByIdIfHasListeners(UserRegistered::class, [$user]);
```

**`dispatchListeners(object $event, ListenerContract ...$listeners): object`** —
Fire an event against a specific set of listeners directly, bypassing whatever
listeners are registered in the event collection. Useful for targeted, ad hoc
dispatch.

```php
$dispatcher->dispatchListeners($event, $listenerOne, $listenerTwo);
```

**`dispatchListener(object $event, ListenerContract $listener): object`** —
Invoke a single listener against an event.

## Passing Arguments to Events

When dispatching by class name via `dispatchById()` or
`dispatchByIdIfHasListeners()`, the dispatcher can populate the event with data
before invoking its listeners. To opt into this, implement
`Valkyrja\Event\Contract\ArgumentsCapableEventContract`:

```php
public function setArguments(array $arguments): static;
```

The dispatcher calls `setArguments()` with the array you provide at the dispatch
call site. Store those arguments as typed properties and expose them through
getters for your listeners to consume:

```php
use Valkyrja\Event\Contract\ArgumentsCapableEventContract;

class UserRegistered implements ArgumentsCapableEventContract
{
    private User $user;

    public function setArguments(array $arguments): static
    {
        $this->user = $arguments[0];
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
```

If the event does not implement `ArgumentsCapableEventContract`, any arguments
provided at the call site are silently ignored.

## Collecting Listener Return Values

By default, listener return values are discarded. If you need to collect what
your listeners return — for example, when building a pipeline where each
listener contributes to a result — implement
`Valkyrja\Event\Contract\DispatchCollectableEventContract`:

```php
public function addDispatch(mixed $dispatch): void;
public function getDispatches(): array;
```

When the dispatcher invokes a listener that returns a value, it calls
`addDispatch()` on the event with that return value. After all listeners have
been invoked, `getDispatches()` returns the full collection in invocation order:

```php
use Valkyrja\Event\Contract\DispatchCollectableEventContract;

class UserRegistered implements DispatchCollectableEventContract
{
    private array $dispatches = [];

    public function addDispatch(mixed $dispatch): void
    {
        $this->dispatches[] = $dispatch;
    }

    public function getDispatches(): array
    {
        return $this->dispatches;
    }
}
```

## Stoppable Events

Events implementing `Psr\EventDispatcher\StoppableEventInterface` are fully
supported. If a listener marks an event as propagation-stopped, the dispatcher
will not invoke subsequent listeners.

## Registering Listeners

Listeners are registered through **event providers**. An event provider extends
`Valkyrja\Event\Provider\Abstract\Provider` or implements
`Valkyrja\Event\Provider\Contract\ProviderContract`. It defines two static
methods:

**`getListenerClasses(): array`** — Returns class names to scan for
`#[Listener]` attributes.

**`getListeners(): array`** — Returns pre-built `ListenerContract` instances for
manual registration.

Event providers are wired into the application through component providers (see
below). Listeners are deferred — they are registered into the event collection
during component loading, but their dispatch targets are not resolved until the
event is actually fired.

### Attribute-Based Registration

The recommended approach for most listeners. Add your listener class names to
`getListenerClasses()` in your event provider:

```php
use Valkyrja\Event\Provider\Abstract\Provider;

class AppEventProvider extends Provider
{
    public static function getListenerClasses(): array
    {
        return [
            SendWelcomeEmail::class,
            NotificationService::class,
        ];
    }
}
```

The framework inspects each class for `#[Valkyrja\Event\Attribute\Listener]`
attributes. The attribute is repeatable, so a single class or method can listen
to multiple events.

**Class-level attribute** — The framework creates a `ClassDispatch` for the
listener, resolving and invoking the entire class when the event fires. The
class should be invokable:

```php
use Valkyrja\Event\Attribute\Listener;

#[Listener(UserRegistered::class)]
class SendWelcomeEmail
{
    public function __invoke(UserRegistered $event): void
    {
        // send the email
    }
}
```

**Method-level attribute** — The framework creates a `MethodClassDispatch` for
that method, resolving the class from the container and calling the attributed
method when the event fires. The method name can be anything:

```php
use Valkyrja\Event\Attribute\Listener;

class NotificationService
{
    #[Listener(UserRegistered::class)]
    public function onUserRegistered(UserRegistered $event): void
    {
        // send notification
    }
}
```

When using class-level dispatch with a class registered as a singleton in the
container, the same instance is reused across multiple event firings. Design
accordingly if the listener handles state.

### Manual Registration

For cases where attribute-based registration is not suitable, return pre-built
listener instances from `getListeners()`. Each instance is a
`Valkyrja\Event\Data\Listener` (or any class implementing `ListenerContract`),
constructed with an event ID, a unique name, and a dispatch:

```php
use Valkyrja\Dispatch\Data\MethodClassDispatch;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Provider\Abstract\Provider;

class AppEventProvider extends Provider
{
    public static function getListeners(): array
    {
        return [
            new Listener(
                eventId:  UserRegistered::class,
                name:     'notification_service.on_user_registered',
                dispatch: new MethodClassDispatch(
                    class:  NotificationService::class,
                    method: 'onUserRegistered',
                ),
            ),
        ];
    }
}
```

## Event Providers and Component Providers

Your event provider is wired into the application through your component
provider's `getEventProviders()` method:

```php
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Abstract\Provider;

class AppComponentProvider extends Provider
{
    public static function getEventProviders(ApplicationContract $app): array
    {
        return [
            AppEventProvider::class,
        ];
    }
}
```

## A Complete Example

```php
use Valkyrja\Event\Attribute\Listener;
use Valkyrja\Event\Contract\ArgumentsCapableEventContract;
use Valkyrja\Event\Contract\DispatchCollectableEventContract;
use Valkyrja\Event\Provider\Abstract\Provider;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Abstract\Provider as ComponentProvider;

// 1. The event
class UserRegistered implements ArgumentsCapableEventContract, DispatchCollectableEventContract
{
    private User $user;
    private array $dispatches = [];

    public function setArguments(array $arguments): static
    {
        $this->user = $arguments[0];
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function addDispatch(mixed $dispatch): void
    {
        $this->dispatches[] = $dispatch;
    }

    public function getDispatches(): array
    {
        return $this->dispatches;
    }
}

// 2. A listener using a method-level attribute
class NotificationService
{
    #[Listener(UserRegistered::class)]
    public function onUserRegistered(UserRegistered $event): string
    {
        // send notification
        return 'notification_sent';
    }
}

// 3. The event provider
class AppEventProvider extends Provider
{
    public static function getListenerClasses(): array
    {
        return [NotificationService::class];
    }
}

// 4. The component provider
class AppComponentProvider extends ComponentProvider
{
    public static function getEventProviders(ApplicationContract $app): array
    {
        return [AppEventProvider::class];
    }
}

// 5. Dispatching the event
$event = $dispatcher->dispatchById(UserRegistered::class, [$user]);
$results = $event->getDispatches(); // ['notification_sent']
```
