# The Container

## Introduction

The container is the backbone of a Valkyrja application. Every major service, component, and object is registered in and resolved through it. Understanding the container means understanding how the framework itself is assembled — and how to extend it for your own application.

Valkyrja's container is **PSR-11 compliant**, meaning any library that accepts a standard PHP container interface will work with it out of the box. Beyond PSR-11, Valkyrja's container adds a richer binding model, explicit service types, and deferred loading that makes the framework fast by default.

## Contracts

Throughout Valkyrja's codebase, interfaces are called **contracts**. This is a deliberate naming choice rooted in the framework's design goal of language portability — other languages like Go and Python don't use the word "interface," but the concept of a contract (a guaranteed set of behaviours) is universal. When you see a class ending in `Contract`, it is an interface.

This convention applies to your own code too. Binding against contracts rather than concrete classes is the recommended pattern — it keeps your code flexible, testable, and aligned with how the framework itself is structured.

## Deferred Loading

The most important thing to understand about Valkyrja's container is that **services are deferred by default**. When the application boots, the container does not instantiate any services. Instead, it builds a map — a lightweight index of which service IDs exist and how to resolve them. A service is only created the first time it is actually requested.

This is what makes Valkyrja fast. The container carries almost no overhead at boot time regardless of how many services are registered. Cost is only paid when a service is used.

## Service Types

Valkyrja's container distinguishes between four types of services. Choosing the right type matters both for correctness and performance.

**Singleton** — A single instance is created on first resolution and reused on every subsequent call. Use this for stateful services that should be shared across the application — database connections, loggers, the mailer, and so on.

**Service** — A new instance is created on every resolution. Use this for stateless objects or anywhere a fresh instance is required per use.

**Callable** — A callback is invoked on every resolution. Use this when the creation logic is too complex or context-dependent to express as a simple class instantiation.

**Alias** — A service ID that maps to another existing service ID in the container. Resolving an alias resolves the underlying service transparently.

## Binding Services

### The ServiceContract

For class-based bindings (singletons and services registered via `bind*` methods), the class must implement `Valkyrja\Container\Contract\ServiceContract`. This contract defines a static `make()` method — the factory the container calls when it needs to create an instance of that class. The `make()` method receives the container itself, so it can resolve its own dependencies:

```php
public static function make(ContainerContract $container): static
{
    return new static(
        $container->getSingleton(SomeDependency::class)
    );
}
```

This design gives each class explicit control over its own instantiation, rather than relying on reflection-based autowiring.

### Binding Methods

**`bind(string $id, string $class)`** — Maps a service ID to a concrete class. The class must implement `ServiceContract`. Every call to `getService($id)` will return a new instance via `$class::make()`.

**`bindSingleton(string $id, string $class)`** — Maps a service ID to a concrete class for singleton resolution. The class must implement `ServiceContract`. The first call creates the instance via `$class::make()`; all subsequent calls return the same instance. Singletons bound this way do not need to be provided through a service provider — the class map is enough.

**`bindAlias(string $alias, string $id)`** — Maps one service ID to another already registered in the container. Resolving the alias resolves the underlying service.

**`setCallable(string $id, callable $callable)`** — Registers a callback against a service ID. The callback is invoked on every resolution.

**`setSingleton(string $id, object $instance)`** — Registers an already-created object against a service ID. The object does not need to implement `ServiceContract`. This is the method service providers use inside their publish callbacks to hand a fully constructed object to the container.

### Checking the Container

The `is*` methods let you inspect what is registered before attempting resolution:

- `has(string $id): bool` — PSR-11 check; returns true if the ID is registered in any form
- `isSingleton(string $id): bool`
- `isService(string $id): bool`
- `isCallable(string $id): bool`
- `isAlias(string $id): bool`

## Resolving Services

**`get(string $id): mixed`** — PSR-11 resolution. Works across all four service types without the caller needing to know which type was registered. Slightly slower than the specific methods due to the additional type lookup.

**`getSingleton(string $id): object`** — Resolves a singleton. If the singleton was registered via `bindSingleton()`, the container calls `make()` on first access and caches the result. If registered via `setSingleton()` through a service provider, the publish callback is invoked on first access and the result cached.

**`getService(string $id): object`** — Resolves a service, always returning a new instance via `make()`.

**`getCallable(string $id): mixed`** — Invokes the registered callable and returns the result.

**`getAliased(string $alias): mixed`** — Resolves the underlying service that the alias points to.

When you know exactly what type you are resolving, prefer the specific method over `get()`. The difference is small per call, but meaningful at scale.

## Service Providers

The primary way to register services in Valkyrja is through **service providers**. A service provider is a class that declares which services it provides and how to publish each one. Services are deferred — the provider's publish callbacks are only invoked when the declared service is first requested.

A service provider extends `Valkyrja\Container\Provider\Abstract\Provider` or implements `Valkyrja\Container\Provider\Contract\ProviderContract`. It defines two things:

**`publishers()`** — a map of service IDs to the static publish callbacks that create them:

```php
public static function publishers(): array
{
    return [
        MyServiceContract::class => [self::class, 'publishMyService'],
    ];
}
```

**`provides()`** — the list of service IDs this provider is responsible for:

```php
public static function provides(): array
{
    return [
        MyServiceContract::class,
    ];
}
```

**The publish callback** — a static method that receives the container and registers the service:

```php
public static function publishMyService(ContainerContract $container): void
{
    $container->setSingleton(
        MyServiceContract::class,
        new MyService(
            $container->getSingleton(SomeDependency::class)
        )
    );
}
```

## Component Providers

Service providers live inside **component providers**, which are the top-level organisational unit of a Valkyrja application. A component provider extends `Valkyrja\Application\Provider\Abstract\Provider` or implements `Valkyrja\Application\Provider\Contract\ProviderContract`. It groups together the service providers, CLI route providers, HTTP route providers, and event listener providers that make up a logical component of your application.

Component providers are registered in the `providers` array of your config class. When the application boots, it iterates through this list and loads each component provider's child providers.

A component provider may optionally implement `Valkyrja\Application\Provider\Contract\PublishableProviderContract`, which adds a `publish()` method. **This method is always called, regardless of whether the data cache is active.** It exists for things that must be registered on every single request — use it sparingly. Any service, route, or event that can be deferred should be registered through the appropriate child provider instead, not through `publish()`. Misusing `publish()` undermines the caching mechanism that makes Valkyrja fast.

## A Complete Example

```php
// 1. The contract
interface LoggerContract { ... }

// 2. The implementation (implements ServiceContract for bind* methods,
//    or leave it out if you'll use setSingleton via a service provider)
class FileLogger implements LoggerContract
{
    public static function make(ContainerContract $container): static
    {
        return new static(
            $container->getSingleton(FilesystemContract::class)
        );
    }
}

// 3. The service provider
class LoggerServiceProvider extends Provider
{
    public static function publishers(): array
    {
        return [
            LoggerContract::class => [self::class, 'publishLogger'],
        ];
    }

    public static function provides(): array
    {
        return [LoggerContract::class];
    }

    public static function publishLogger(ContainerContract $container): void
    {
        $container->setSingleton(
            LoggerContract::class,
            FileLogger::make($container)
        );
    }
}

// 4. The component provider (registered in your config's providers array)
class AppComponentProvider extends Provider
{
    public static function serviceProviders(): array
    {
        return [LoggerServiceProvider::class];
    }
}
```

With this in place, `LoggerContract::class` exists in the container's map from boot time, but `FileLogger` is never instantiated until something calls `$container->getSingleton(LoggerContract::class)`.
