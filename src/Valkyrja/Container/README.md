# The Container

## Introduction

The container registers and resolves every service in a Valkyrja application.
It is **PSR-11 compliant**: any library that accepts
`Psr\Container\ContainerInterface` can use it. Beyond PSR-11, the container
adds an explicit binding model, three service types, and deferred loading.

## Contracts

Valkyrja calls an interface a **contract**; a class or file name that ends in
`Contract` is an interface. Bind against a contract rather than a concrete
class where you can — the framework itself does.

## Deferred Loading

Services are **deferred by default**. At boot the container instantiates
nothing. It stores a map of service ids and the callbacks that build them.
The container creates a singleton on its first request and caches it. The
container creates a service on every request. Boot cost therefore does not
grow with the number of registered services.

## Service Types

The container has three registration types.

**Singleton** — The container creates one instance on the first resolution and
reuses it on every later call. Use this for shared stateful services: a
database connection, a logger, the event dispatcher.

**Service** — The container creates a new instance on every resolution. Use
this for stateless objects, or where each caller must own a fresh instance.

**Alias** — A service id that maps to another registered service id. A
resolution of the alias resolves the target service.

## Binding Services

In application code, a service provider registers most services;
[Service Providers](#service-providers) shows the pattern. The methods below
are what publish callbacks and boot code call.

### The Callable Signature

`bind()` and `bindSingleton()` accept any `callable` with the signature
`(ContainerContract $container, array $arguments): object`. A static factory
method on the service class satisfies that signature, so an array callable can
point to one:

```php
use Valkyrja\Container\Manager\Contract\ContainerContract;

class UserRepository implements UserRepositoryContract
{
    public static function make(ContainerContract $container, array $arguments = []): static
    {
        return new static(
            $container->getSingleton(DatabaseContract::class)
        );
    }
}

$container->bind(UserRepositoryContract::class, [UserRepository::class, 'make']);
```

This is a valid alternative, not the default. It moves registration into the
service class. Prefer a service provider unless the class owns a construction
step that callers must reuse.

Neither approach uses reflection-based autowiring. Every dependency is
declared in code.

### Binding Methods

**`bind(string $id, callable $callable): static`** — Binds a service id to a
callable factory. Every call to `getService($id)` invokes the callable and
returns a fresh instance.

**`bindSingleton(string $id, callable $callable): static`** — Same as
`bind()`, but singleton-scoped. The container invokes the callable once on
the first resolution and caches the result.

**`bindAlias(string $alias, string $id): static`** — Maps one service id to
another id already registered in the container.

**`setSingleton(string $id, object $singleton): static`** — Registers an
already-constructed object directly. Publish callbacks use this method when
they build the instance inline.

### Checking Registrations

Before a resolution, you can inspect what is registered:

- `has(string $id): bool` — PSR-11; true when the id is registered in any
  form, a deferred publish callback included.
- `isSingleton(string $id): bool` — a singleton binding or a resolved
  instance exists.
- `isSingletonBinding(string $id): bool` — a binding exists that is not yet
  resolved.
- `isSingletonInstance(string $id): bool` — the singleton is resolved and
  cached.
- `isService(string $id): bool` — a service binding exists.
- `isAlias(string $id): bool` — the id is an alias.

`isSingleton($id)` equals `isSingletonBinding($id) || isSingletonInstance($id)`.
The fine-grained pair separates "registered but not yet built" from "resolved
and reusable" — the distinction that child containers rely on (see
[Child Containers](#child-containers)).

## Resolving Services

**`get(string $id, array $arguments = [], InvalidReferenceMode $mode = InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION): object`**
— PSR-11 resolution. It checks singletons first, then services, then aliases,
so the caller does not need to know the registration type. `$arguments` go to
the service callable when the id resolves to a service.

When the id is not registered at all, `$mode` decides the fallback:

- `InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION` (the default) —
  when `$id` is an existing class, the container returns
  `new $id(...$arguments)`, without registration and without caching.
  Otherwise it throws `ContainerInvalidReferenceException`.
- `InvalidReferenceMode::THROW_EXCEPTION` — the container throws
  `ContainerInvalidReferenceException` immediately.

**`getSingleton(string $id): object`** — Resolves a singleton. The first
access invokes the binding or the publish callback and caches the result.
Later calls return the cached instance.

**`getService(string $id, array $arguments = []): object`** — Resolves a
service. Every call invokes the registered callable with `$arguments` and
returns a fresh instance.

**`getAliased(string $id, array $arguments = []): object`** — Resolves the
service that the alias points to.

The three type-specific methods throw `ContainerInvalidReferenceException`
when the id is not registered as that type; they do not fall back to
`new $id()`. When you know the registration type, prefer the specific
method over `get()`. The saved lookup is small per call but adds up in a hot
path such as route dispatch.

## Service Providers

A **service provider** registers a service. This is the convention the
framework follows everywhere. The provider owns the registration logic; the
service class implements its contract and carries no registration code.

A service provider implements
`Valkyrja\Container\Provider\Contract\ServiceProviderContract`, which has one
instance method, `publishers()`. It returns a map from service ids to static
publish callbacks. The container stores the map and defers each callback until
the first request for that id:

```php
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

class NotifierServiceProvider implements ServiceProviderContract
{
    public function publishers(): array
    {
        return [
            NotifierContract::class => [self::class, 'publishNotifier'],
        ];
    }

    public static function publishNotifier(ContainerContract $container): void
    {
        $container->setSingleton(
            NotifierContract::class,
            new SlackNotifier()
        );
    }
}
```

To swap the implementation, change the publish callback. The contract and the
callers do not change.

A publish callback can resolve other services from the container. Those
services are themselves deferred; the resolution triggers their own publish
callbacks when needed.

A component provider hands the providers to the application through
`getContainerProviders()`:

```php
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;

class AppComponentProvider implements ComponentProviderContract
{
    public function getContainerProviders(ApplicationContract $app): array
    {
        return [new NotifierServiceProvider()];
    }

    // The other ComponentProviderContract methods are omitted here.
}
```

### Binding Without a Provider

A component provider can also bind a service directly, paired with a static
factory ([The Callable Signature](#the-callable-signature)):

```php
public function getContainerProviders(ApplicationContract $app): array
{
    $app->getContainer()->bindSingleton(
        NotifierContract::class,
        [SlackNotifier::class, 'make']
    );

    return [];
}
```

The two approaches combine in one application, but not for one id: a boot-time
`bind()` or `bindSingleton()` marks the id as published, so a publish callback
registered for the same id never runs. Register each id through one approach
only.

## Child Containers

A child container is a per-request container. It inherits the parent's state
at no cost and writes only to its own local maps. This is the isolation
mechanism that Valkyrja's persistent worker entry points (FrankenPHP,
OpenSwoole, RoadRunner) use to keep request-scoped state out of the parent.

### The Parent/Child Invariant

The parent container bootstraps once when the worker process starts and is
then **frozen** — nothing may write to it again. Each incoming request
receives a fresh child container. The child checks its own maps first; when an
id is not registered locally, the child falls back to the parent read-only.
When the request ends, the child is discarded and the parent is unchanged.

Deferred services stay available in a child. The child receives the parent's
publish callbacks through `ContainerData`, so the first lookup of an
unpublished service runs its callback with the child as the container. The
service publishes into the child's own scope; the parent's maps do not change.

`WorkerHttp::bootstrapParentServices()` exists for cost, not for correctness.
A service that it force-resolves before the request loop is cached in the
frozen parent once, and every child reuses that instance. A service left
deferred publishes again in each child that requests it.

### ContainerData

Before the request loop begins, capture the parent's state once:

```php
$data = $app->getContainer()->getData();
```

`getData()` returns a `ContainerData` value object with four maps: `aliases`,
`callbacks`, `services`, and `singletons`. Because PHP arrays are
copy-on-write, each child gets its own logical copy at no cost until it writes
to one.

`ChildContainer` copies `callbacks` and `singletons` from the data. Alias and
service lookups need no copy; they fall back through the parent's methods.

### Resolution Order

Each lookup checks the child's own maps first, then falls back to the parent.
For a singleton the child applies a three-step strategy:

1. **The child has a cached instance** — return it.
2. **The parent has a cached instance** — reuse it; the parent is frozen, so
   the instance does not change.
3. **The child has a binding** — create a fresh instance in the child's scope
   only.

`isPublished` follows the same child-first, parent-fallback pattern. When the
parent has already published a service, the child does not publish it again.

### Available Implementations

The component provides two implementations.

**`Valkyrja\Container\Manager\ChildContainer`** — The default. It reads the
parent through `ContainerContract` methods, so any contract implementation can
be the parent. This is the portable, cross-language implementation.

**`Valkyrja\Container\Manager\NativeChildContainer`** — PHP-specific. It reads
the parent's protected maps directly, the publish callbacks included, which
removes the method-call overhead on the fallback path. Its constructor takes
only a concrete `Container` parent and no `ContainerData`. Use it only when a
profile shows a bottleneck at very high child construction rates.

### Using a Child Container

```php
use Valkyrja\Container\Manager\ChildContainer;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;

// Once, before the request loop:
$parent = $app->getContainer();
$data   = $parent->getData();

// Per request, inside the loop:
$child = new ChildContainer($parent, $data);

// Register request-scoped services on the child only:
$child->setSingleton(ServerRequestContract::class, $request);

// Resolve as normal; the child falls back to the parent:
$handler = $child->getSingleton(RequestHandlerContract::class);
```

In practice you do not construct child containers directly. The worker entry
classes (`WorkerHttp` and its subclasses) do this for every request. See the
[Application README](../Application/README.md#persistent-worker-lifecycle) for
the full lifecycle.
