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
flexible, testable, and aligned with how the framework itself is structured.

## Deferred Loading

The most important thing to understand about Valkyrja's container is that *
*services are deferred by default**. When the application boots, the container
does not instantiate anything. Instead, it builds a lightweight map — a record
of which service IDs exist and how to resolve them when asked. A service is only
created the first time it is actually requested.

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
$container->has(string $id): bool          // PSR-11; true if registered in any form
$container->isSingleton(string $id): bool
$container->isService(string $id): bool
$container->isCallable(string $id): bool
$container->isAlias(string $id): bool
```

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
two things:

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

## Component Providers

Service providers live inside **component providers**, which are the top-level
organisational unit of a Valkyrja application. A component provider extends
`Valkyrja\Application\Provider\Abstract\Provider` or implements
`Valkyrja\Application\Provider\Contract\ProviderContract`. It groups the service
providers, CLI route providers, HTTP route providers, and event listener
providers that make up a logical component of your application.

Component providers are registered in your config's `providers` array. When the
application boots, it calls `getContainerProviders()`, `getEventProviders()`,
`getCliProviders()`, and `getHttpProviders()` on each component provider to
collect all child providers.

```php
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Abstract\Provider;

class AppComponentProvider extends Provider
{
    public static function getContainerProviders(ApplicationContract $app): array
    {
        return [
            CacheServiceProvider::class,
            MailServiceProvider::class,
        ];
    }

    public static function getHttpProviders(ApplicationContract $app): array
    {
        return [
            AppRouteProvider::class,
        ];
    }

    public static function getEventProviders(ApplicationContract $app): array
    {
        return [
            AppEventProvider::class,
        ];
    }
}
```

A component provider may additionally implement `PublishableProviderContract`,
which adds a `publish(ApplicationContract $app)` method that **runs on every
boot, cached or not**. Use this only for registrations that genuinely cannot be
deferred. Binding services or routes here defeats the caching mechanism
entirely.

## A Complete Example

```php
// 1. The contract
interface NotifierContract
{
    public function notify(string $message): void;
}

// 2. The implementation
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

// 3. The service provider
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
            SlackNotifier::make($container)
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

With this in place, `NotifierContract::class` is known to the container at boot
time, but `SlackNotifier` is never instantiated until something calls
`$container->getSingleton(NotifierContract::class)`.
