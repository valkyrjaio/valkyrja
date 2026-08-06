# Event Dispatching

## Introduction

The Event component implements [PSR-14](https://www.php-fig.org/psr/psr-14/).
Any PSR-14 event object works with the dispatcher, and the dispatcher serves
anywhere a `Psr\EventDispatcher\EventDispatcherInterface` is expected. On top
of PSR-14, the component adds attribute-based listener registration, the
collection of listener return values, and stoppable-event support.

## Core Concepts

An **event** is a plain PHP object with no required base class. It carries the
data for one occurrence in the application.

A **listener** connects one event class to one **handler**. A handler is a
callable that the dispatcher invokes when the event is dispatched.

The **dispatcher** —
`Valkyrja\Event\Dispatcher\Contract\EventDispatcherContract` — finds the
listeners for an event and invokes each handler in order.

## Dispatching Events

The dispatcher contract declares six methods:

```php
public function dispatch(object $event): object;
public function dispatchIfHasListeners(object $event): object;
public function dispatchById(string $eventId, array $arguments = []): object;
public function dispatchByIdIfHasListeners(string $eventId, array $arguments = []): object;
public function dispatchListeners(object $event, ListenerContract ...$listeners): object;
public function dispatchListener(object $event, ListenerContract $listener): object;
```

**`dispatch()`** — the PSR-14 method. Pass a constructed event object. The
dispatcher invokes every registered handler in order and returns the event.

```php
$dispatcher->dispatch(new UserRegistered($user));
```

**`dispatchById()`** — dispatch by class name. The container resolves the
class name into the event, and the dispatcher then invokes the listeners —
see [Resolving Events From the Container](#resolving-events-from-the-container).
The dispatcher throws an `EventInvalidEventException` when the container
returns a different type.

```php
$dispatcher->dispatchById(UserRegistered::class, [$user]);
```

**`dispatchListeners()`** — dispatch an event against the given listeners
only. The dispatcher does not consult the registered collection.

**`dispatchListener()`** — invoke one listener's handler against an event.

The `dispatchIfHasListeners()` and `dispatchByIdIfHasListeners()` variants
invoke the listeners only when at least one listener is registered for the
event. `dispatchIfHasListeners()` otherwise returns the event unchanged.
`dispatchByIdIfHasListeners()` resolves the event from the container either
way, and returns it.

## Resolving Events From the Container

`dispatchById()` and `dispatchByIdIfHasListeners()` take a class name, not an
event object. The dispatcher asks the container for that class name and passes
the call-site arguments along. The container constructs an unbound event class
itself and passes the call-site arguments to the constructor. A class name
that the container cannot resolve throws a
`ContainerInvalidReferenceException`.

Bind each event that you dispatch by class name. The binding is a callable, so
you decide how the container builds the event:

```php
use Valkyrja\Container\Manager\Contract\ContainerContract;

$container->bind(
    UserRegistered::class,
    static fn (ContainerContract $container, array $arguments): UserRegistered => new UserRegistered($arguments[0]),
);
```

## Listener Handlers

A handler has the signature:

```php
callable(ContainerContract $container, array<string, mixed> $arguments): mixed
```

The dispatcher invokes the handler as:

```php
$handler($container, ['event' => $event]);
```

The `$arguments` array holds the dispatched event under the `event` key. The
handler resolves its dependencies from the container.

A handler that is an array callable, such as
`[NotificationService::class, 'handle']`, can be written to a generated data
file. A closure cannot. Prefer an array callable, so the listener set stays
cacheable.

## Event Contracts

Two optional contracts change how the dispatcher treats an event, and one PSR
interface stops propagation.

### `ArgumentsCapableEventContract`

`Valkyrja\Event\Contract\ArgumentsCapableEventContract` declares one method:

```php
public function setArguments(array $arguments): static;
```

When `dispatchById()` or `dispatchByIdIfHasListeners()` resolves an event
that implements this contract, the dispatcher calls `setArguments()` with the
arguments from the call site.

### `DispatchCollectableEventContract`

By default, the dispatcher discards each handler's return value. To collect
the return values, implement
`Valkyrja\Event\Contract\DispatchCollectableEventContract`:

```php
public function addDispatch(mixed $dispatch): void;
public function getDispatches(): array;
```

After each handler runs, the dispatcher passes the handler's return value to
`addDispatch()`. `getDispatches()` returns every collected value in invocation
order.

```php
use Valkyrja\Event\Contract\DispatchCollectableEventContract;

class UserRegistered implements DispatchCollectableEventContract
{
    /** @var array<int, mixed> */
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

### Stoppable Events

An event may implement `Psr\EventDispatcher\StoppableEventInterface`. After
each handler runs, the dispatcher checks `isPropagationStopped()`. When the
check returns `true`, the dispatcher returns the event and does not invoke the
remaining listeners.

## Registering Listeners

Listeners register through a listener provider — a class that implements
`Valkyrja\Event\Provider\Contract\ListenerProviderContract`:

```php
public function getListenerClasses(): array;
public function getListeners(): array;
```

`getListenerClasses()` returns class names that the framework scans for
`#[Listener]` attributes. `getListeners()` returns constructed
`ListenerContract` instances. The framework collects every listener provider
from the component providers and registers each listener into the listener
collection. A handler runs only when its event is dispatched.

### Attribute Registration

Return class names from `getListenerClasses()`. The framework scans each class
for `#[Valkyrja\Event\Attribute\Listener]` on the class and on its methods.
The attribute is repeatable, so one class or method can listen to more than
one event.

`#[Listener]` takes the event class name, a unique listener name, and an
optional handler. A companion `#[ListenerHandler]` attribute on the same class
or method can supply the handler instead.

Warning: a `#[Listener]` with no handler — none on the attribute and no
companion `#[ListenerHandler]` — registers a listener whose handler does
nothing and returns `null`. The dispatch reports no error. Always supply a
handler.

PHP permits a closure in an attribute argument only from PHP 8.5. This
package supports PHP 8.4, so an attribute handler is an array callable, and
the named method must be static:

```php
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Event\Attribute\Listener;
use Valkyrja\Event\Attribute\ListenerHandler;

class NotificationService
{
    #[Listener(UserRegistered::class, 'notification.user_registered')]
    #[ListenerHandler([self::class, 'handle'])]
    public function onUserRegistered(UserRegistered $event): void
    {
        // Send the notification.
    }

    /** @param array<string, mixed> $arguments */
    public static function handle(ContainerContract $container, array $arguments): void
    {
        $container->get(self::class)->onUserRegistered($arguments['event']);
    }
}
```

The same pair also works at the class level, and the handler can pass as the
third `#[Listener]` argument in place of a `#[ListenerHandler]`.

The listener provider names the class:

```php
use Valkyrja\Event\Provider\Contract\ListenerProviderContract;

class AppListenerProvider implements ListenerProviderContract
{
    public function getListenerClasses(): array
    {
        return [NotificationService::class];
    }

    public function getListeners(): array
    {
        return [];
    }
}
```

### Manual Registration

Return constructed listeners from `getListeners()`. Each one is a
`Valkyrja\Event\Data\Listener` — or any `ListenerContract` implementation —
built from an event class name, a unique listener name, and a handler. The
handler may be any callable, including a closure:

```php
use Valkyrja\Event\Data\Listener;

public function getListeners(): array
{
    return [
        new Listener(
            eventId: UserRegistered::class,
            name: 'notification.user_registered',
            handler: [NotificationService::class, 'handle'],
        ),
    ];
}
```

## Wiring Through a Component Provider

The application reads listener providers from each component provider's
`getEventProviders()` method. The method belongs to
`Valkyrja\Application\Provider\Contract\ComponentProviderContract`, which the
Application component's README documents in full:

```php
use Valkyrja\Application\Kernel\Contract\ApplicationContract;

public function getEventProviders(ApplicationContract $app): array
{
    return [
        new AppListenerProvider(),
    ];
}
```

## Service Registration

The Event service provider registers the following singletons:

| Contract / Class             | Description                                            |
| :--------------------------- | :----------------------------------------------------- |
| `EventDispatcherContract`    | The dispatcher (`EventDispatcher`)                     |
| `ListenerCollectionContract` | The listener collection                                |
| `ListenerCollectorContract`  | The attribute collector (`AttributeListenerCollector`) |
| `EventData`                  | The generated listener data                            |
