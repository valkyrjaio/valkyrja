# The Container

## Introduction

The container is the backbone of a Valkyrja application. Every service,
component, and object is registered in and resolved through it. Understanding
the container means understanding how the entire framework is assembled — and
how to extend it cleanly for your own application.

Valkyrja's container is **PSR-11 compliant**, meaning any library that accepts
`Psr\Container\ContainerInterface` will work with it out of the box. Beyond
PSR-11, Valkyrja's container adds an explicit binding model, four distinct
service types, and deferred loading that makes the framework fast by default.

## Contracts

Throughout Valkyrja's codebase, interfaces are called **contracts**. This naming
is intentional and rooted in the framework's goal of language portability — the
concept of a contract (a guaranteed set of behaviours that a type must fulfil)
is universal across languages, where the word "interface" is not. When you see a
class or file ending in `Contract`, it is an interface.

This convention applies to your own code too. Binding against contracts rather
than concrete classes is the recommended pattern — it keeps your application
flexible, testable, and aligned with how the framework itself is structured, but
it is not a hard and fast rule.

## Deferred Loading

The most important thing to understand about Valkyrja's container is that
**services are deferred by default**. When the application boots, the container
does not instantiate anything. Instead, it builds a lightweight map — a record
of which service IDs exist and how to resolve them when asked. A service is only
created the first time it is actually requested for singletons, and each time it
is requested in the case of services and callables.

This is what makes Valkyrja fast. The container carries virtually no boot-time
overhead regardless of how many services are registered. Cost is paid only when
a service is used.

## Service Types

Valkyrja's container distinguishes between four types of registrations. Choosing
the right type matters for both correctness and performance.

**Singleton** — A single instance is created on first resolution and reused on
every subsequent call. Use this for stateful services that should be shared
across the application: database connections, loggers, the event dispatcher.

**Service** — A new instance is created on every resolution. Use this for
stateless objects or anywhere a fresh instance is required per caller.

**Callable** — A callback function is invoked on every resolution. Use this when
creation logic is too complex or context-dependent to express as a simple class
instantiation.

**Alias** — A service ID that maps to another registered service ID. Resolving
an alias resolves the underlying service transparently.

## Binding Services

### ServiceContract

For class-based bindings registered via `bind()` or `bindSingleton()`, the class
must implement `Valkyrja\Container\Contract\ServiceContract`. This contract
defines a single static factory method:

```php
public static function make(ContainerContract $container, array $arguments = []): static;
```

The `make()` method receives the container and an optional arguments array, so
it can resolve its own dependencies explicitly:

```php
use Valkyrja\Container\Contract\ServiceContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;

class UserRepository implements UserRepositoryContract, ServiceContract
{
    public static function make(ContainerContract $container, array $arguments = []): static
    {
        return new static(
            $container->getSingleton(DatabaseContract::class)
        );
    }
}
```

This design gives each class explicit ownership of its own instantiation, rather
than relying on reflection-based autowiring. There is no magic — every
dependency is declared in code.

### Binding Methods

**`bind(string $id, string $class)`** — Maps a service ID to a class. The class
must implement `ServiceContract`. Every call to `getService($id)` returns a new
instance via `$class::make()`.

**`bindSingleton(string $id, string $class)`** — Same, but singleton-scoped. The
class must implement `ServiceContract`. The first resolution calls
`$class::make()` and caches the result; all subsequent calls return the same
instance.

**`bindAlias(string $alias, string $id)`** — Maps one service ID to another
already registered in the container.

**`setCallable(string $id, callable $callable)`** — Registers a callback. The
callback is invoked on every resolution.

**`setSingleton(string $id, object $instance)`** — Registers an
already-constructed object. The class does not need to implement
`ServiceContract`. This is the method service providers use inside their publish
callbacks.

### Checking Registrations

Before resolving, you can inspect what is registered:

```php
$container->has(string $id): bool                  // PSR-11; true if registered in any form
$container->isSingleton(string $id): bool          // true if binding OR resolved instance exists
$container->isSingletonBinding(string $id): bool   // true if class binding exists (not yet resolved)
$container->isSingletonInstance(string $id): bool  // true if already resolved and cached
$container->isService(string $id): bool
$container->isCallable(string $id): bool
$container->isAlias(string $id): bool
```

`isSingleton` is equivalent to `isSingletonBinding || isSingletonInstance`. The
two fine-grained methods are useful when you need to distinguish between "this
singleton is registered but not yet built" and "this singleton is already live
and can be reused" — which is exactly the distinction child containers rely on
(see [Child Containers](#child-containers)).

## Resolving Services

**`get(string $id): mixed`** — PSR-11 resolution. Works across all four types
without the caller needing to know which type was registered. Slightly slower
than the type-specific methods due to the additional lookup.

**`getSingleton(string $id): object`** — Resolves a singleton. On first access
the container calls the registered publish callback (or `make()`) and caches the
result. All subsequent calls return the cached instance without any additional
work.

**`getService(string $id): object`** — Resolves a service, always returning a
fresh instance via `make()`.

**`getCallable(string $id): mixed`** — Invokes the registered callable and
returns its result.

**`getAliased(string $alias): mixed`** — Resolves the service the alias points
to.

When you know the type of what you are resolving, prefer the specific method
over `get()`. The difference is small per call but meaningful at scale —
especially in a hot path like route dispatch.

## Service Providers

The primary way to register services is through **service providers**. A service
provider is a class that declares which services it provides and how to
construct them when they are first requested.

A service provider extends `Valkyrja\Container\Provider\Abstract\Provider` or
implements `Valkyrja\Container\Provider\Contract\ProviderContract`. It defines
the following things:

**`publishers()`** — A map of service IDs to the static factory methods that
create them:

```php
public static function publishers(): array
{
    return [
        CacheContract::class => [self::class, 'publishCache'],
    ];
}
```

**`provides()`** — The complete list of service IDs this provider is responsible
for. The container uses this to know the provider exists without loading it:

```php
public static function provides(): array
{
    return [
        CacheContract::class,
    ];
}
```

**The publish callback** — A static method that receives the container and
registers the service. This is only ever called on the first request for that
service:

```php
public static function publishCache(ContainerContract $container): void
{
    $container->setSingleton(
        CacheContract::class,
        new RedisCache(
            $container->getSingleton(RedisClientContract::class)
        )
    );
}
```

The publish callback can resolve other services from the container freely. Those
services are themselves deferred — resolving them here triggers their own
publish callbacks if they haven't been resolved yet.

## Child Containers

A child container is a per-request container that inherits the parent's frozen
state at zero cost and writes only to its own local maps. This is the isolation
mechanism used by Valkyrja's persistent worker entry points (FrankenPHP,
OpenSwoole, RoadRunner) to ensure that request-scoped state never bleeds between
concurrent requests.

### The Parent/Child Invariant

The parent container is bootstrapped once when the worker process starts and
then **frozen** — nothing may write to it again. Each incoming request receives
a fresh child container. The child checks its own maps first; if a service is
not registered locally it falls back to the parent. When the request ends the
child is discarded; the parent is unmodified.

### ContainerData

Before the request loop begins, the parent's `getData()` is captured once:

```php
$data = $app->getContainer()->getData();
```

`getData()` returns a `ContainerData` value object. It is passed to every child
on construction. Because PHP arrays are copy-on-write, each child gets its own
logical copy of the maps at zero cost until it writes to one.

### Resolution Order

For each lookup the child follows this order:

1. **Child's own maps** — anything registered or resolved locally this request
2. **Parent** — read-only fallback; the parent is never written to through the child

Singletons resolved in the child are cached in the child only. The parent's
instance map is never modified after `bootstrap()`.

For singleton resolution specifically, the child applies this three-step strategy:

1. **Child has a cached instance** — return it directly (child-local write, highest priority)
2. **Parent has a cached instance** — reuse it safely; the parent is frozen so the instance will not change
3. **Child has a class binding** — create a fresh instance in the child's scope only

`isPublished` follows the same child-first, parent-fallback pattern. If the
parent has already published a service, the child treats it as published and
does not re-publish it — preserving the parent's frozen state.

### Available Implementations

Two implementations are provided:

Both implementations share the same invariant: neither triggers deferred
resolution in the parent. A lookup on the child will reuse a parent singleton
only if it is already a resolved instance (`isSingletonInstance`). Services that
are still in the deferred map — registered but never force-resolved — are
invisible to child containers. Ensure everything needed at request time is
eagerly resolved in `bootstrapParentServices()` before the request loop begins.

**`Valkyrja\Container\Manager\ChildContainer`** — The default. Delegates to the
parent via `ContainerContract`, meaning it works with any parent that implements
the contract. This is the portable, cross-language implementation.

**`Valkyrja\Container\Manager\NativeChildContainer`** — PHP-specific. Reads fall
back to the parent's maps via direct protected-field access rather than method
calls, eliminating any risk of accidentally triggering deferred publishing or
writing to parent state. Requires a concrete `Container` parent. Use only when
profiling confirms a bottleneck at very high child construction rates.

### Using a Child Container

```php
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\ChildContainer;

// Once, before the request loop:
$parent = $app->getContainer();
$data   = $parent->getData();

// Per request, inside the loop:
$child = new ChildContainer($parent, new ContainerData(
    deferredCallback: $data->deferredCallback,
    singletons: $data->singletons,
));

// Register request-scoped services on the child only:
$child->setSingleton(RequestContract::class, $request);

// Resolve as normal — falls back to parent transparently:
$handler = $child->getSingleton(RequestHandlerContract::class);
```

In practice you will not construct child containers directly. The worker entry
classes (`WorkerHttp` and its subclasses) handle this for every request. See the
[Application README](../Application/README.md#persistent-worker-lifecycle) for
the full lifecycle.

### Singleton State Methods

Child containers rely on the two fine-grained singleton state methods to decide
how to handle a lookup:

- `isSingletonBinding` — the service is registered as a singleton class but has
  not yet been resolved. The child should create a fresh instance in its own
  scope.
- `isSingletonInstance` — the service has already been resolved and cached. If
  only the parent has the instance, the child can reuse it safely (the parent is
  frozen). If the child has its own instance, that takes priority.

Both methods check the child's own state first, then fall back to the parent.

## A Complete Example

### Using a Service Provider

```php
// 1. The contract
interface NotifierContract
{
    public function notify(string $message): void;
}

// 2 The implementation with a ServiceContract
class SlackNotifier implements NotifierContract, ServiceContract
{
    public function __construct(private string $webhookUrl) {}

    public static function make(ContainerContract $container, array $arguments = []): static
    {
        $config = $container->getSingleton(HttpConfig::class);

        return new static($config->key); // illustrative
    }

    public function notify(string $message): void
    {
        // send to Slack
    }
}

// 2 The implementation with a ServiceContract
class TeamsNotifier implements NotifierContract
{
    public function __construct() {}

    public function notify(string $message): void
    {
        // send to Teams
    }
}

// 3. Using a service provider
class NotifierServiceProvider extends Provider
{
    public static function publishers(): array
    {
        return [
            NotifierContract::class => [self::class, 'publishNotifier'],
        ];
    }

    public static function provides(): array
    {
        return [NotifierContract::class];
    }

    public static function publishNotifier(ContainerContract $container): void
    {
        $container->setSingleton(
            NotifierContract::class,
            TeamsNotifier::make($container)
        );
    }
}

// 4. The component provider
class AppComponentProvider extends Provider
{
    public static function getContainerProviders(ApplicationContract $app): array
    {
        return [NotifierServiceProvider::class];
    }
}
```

### Binding without a Service Provider

```php
// 1. The contract
interface NotifierContract
{
    public function notify(string $message): void;
}

// 2 The implementation with a ServiceContract
class SlackNotifier implements NotifierContract, ServiceContract
{
    public function __construct(private string $webhookUrl) {}

    public static function make(ContainerContract $container, array $arguments = []): static
    {
        $config = $container->getSingleton(HttpConfig::class);

        return new static($config->key); // illustrative
    }

    public function notify(string $message): void
    {
        // send to Slack
    }
}

// 3. The component provider
class AppComponentProvider extends Provider
{
    public static function getContainerProviders(ApplicationContract $app): array
    {
        $app->getContainer()->bindSingleton(
            NotifierContract::class,
            SlackNotifier::class
        );

        return [];
    }
}
```

With this in place, `NotifierContract::class` is known to the container at boot
time, but `SlackNotifier` or `TeamsNotifier` (depending on implementation) is
never instantiated until something calls
`$container->getSingleton(NotifierContract::class)`
or `$container->get(NotifierContract::class)`.

> NOTE: These two methodologies can be used together, you don't need to choose
> one or the other, and they can be used together. Be sure not to override a
> contract with two separate implementations, as would be the case if these two
> examples were combined.
