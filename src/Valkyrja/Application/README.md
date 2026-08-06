# The Application

The application class, `Valkyrja\Application\Kernel\Valkyrja`, ties a Valkyrja
project together. It holds the container, carries the config, and collects the
component providers. An entry class bootstraps it once at the entry point.

## Entry Classes

Do not instantiate `Valkyrja` directly. Use an entry class:

- `Valkyrja\Application\Entry\Http` — PHP-FPM / CGI web applications
- `Valkyrja\Application\Entry\Cli` — console applications
- Worker entry classes — persistent worker runtimes (see
  [Persistent Worker Lifecycle](#persistent-worker-lifecycle))

The worker entry classes live in this package. Each extends
`Valkyrja\Application\Entry\Abstract\WorkerHttp`:

| Class                                                  | Runtime                                                          |
| ------------------------------------------------------ | ---------------------------------------------------------------- |
| `Valkyrja\Application\Entry\FrankenPhp\FrankenPhpHttp` | [FrankenPHP](https://frankenphp.dev/docs/worker/)                |
| `Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp` | [OpenSwoole](https://openswoole.com/)                            |
| `Valkyrja\Application\Entry\RoadRunner\RoadRunnerHttp` | [RoadRunner](https://docs.roadrunner.dev/docs/php-worker/worker) |

`Http` and `Cli` expose a single static `run()` method. Call it with a config
object:

```php
// app/public/index.php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Http;

Http::run(new HttpConfig(
    namespace:   'App',
    dir:         __DIR__ . '/..',
    environment: 'production',
    debugMode:   false,
    timezone:    'UTC',
    key:         'your-application-key',
));
```

```php
// app/bin/cli
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Entry\Cli;

Cli::run(new CliConfig(
    namespace:          'App',
    dir:                __DIR__ . '/..',
    environment:        'production',
    debugMode:          false,
    timezone:           'UTC',
    key:                'your-application-key',
    applicationName:    'myapp',
    defaultCommandName: 'list',
));
```

Each named argument overrides one constructor default. The examples spell out
the common overrides; pass only the values that differ in your application.

## Configuration

Configuration is a typed PHP object. There is no `.env` reader and no flat
array registry. Three config classes exist, one per entry type. `HttpConfig`
and `CliConfig` do not extend `Config` — each implements its own contract
(`HttpConfigContract`, `CliConfigContract`) and repeats the base properties.
The entry classes discriminate on the contract: `Http::run()` requires an
`HttpConfigContract`, and `Cli::run()` requires a `CliConfigContract`.

Convention: hold your application's real values in the config object that the
entry point builds. Create one config file per environment, or read the values
from an env file in your own bootstrap, and pass them as constructor
arguments. The constructor defaults are generic placeholders, not production
values.

### Base Properties

`Valkyrja\Application\Data\Config` carries the properties that all three
classes share:

| Property        | Default                 | Description                                          |
| --------------- | ----------------------- | ---------------------------------------------------- |
| `namespace`     | `'App'`                 | The application's root namespace                     |
| `dir`           | `__DIR__`               | The application's root directory — set it explicitly |
| `version`       | framework version       | The application's version string                     |
| `environment`   | `'production'`          | The environment name                                 |
| `debugMode`     | `false`                 | Enable the Whoops throwable handler                  |
| `timezone`      | `'UTC'`                 | PHP's default timezone, set at boot                  |
| `key`           | `'some_secret_app_key'` | The application secret key — always override it      |
| `dataPath`      | `'App/Provider/Data'`   | The framework does not read this property            |
| `dataNamespace` | `'App\\Provider\\Data'` | The framework does not read this property            |
| `providers`     | see below               | The component providers to load                      |
| `callbacks`     | `[]`                    | Callables the bootstrap invokes with the application |

Because the config is plain PHP, any source can supply a value:

```php
use Valkyrja\Application\Data\Config;

new Config(
    environment: $_ENV['APP_ENV'] ?? 'production',
    debugMode: ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
    key: $_ENV['APP_KEY'] ?? throw new RuntimeException('APP_KEY is not set'),
);
```

### HTTP Properties

`Valkyrja\Application\Data\HttpConfig` adds seven middleware lists. Each entry
is a middleware class name.

| Property                    | Default                                                                       |
| --------------------------- | ----------------------------------------------------------------------------- |
| `requestReceivedMiddleware` | `[]`                                                                          |
| `routeMatchedMiddleware`    | `[]`                                                                          |
| `routeNotMatchedMiddleware` | `[ViewRouteNotMatchedMiddleware::class]`                                      |
| `routeDispatchedMiddleware` | `[]`                                                                          |
| `throwableCaughtMiddleware` | `[LogThrowableCaughtMiddleware::class, ViewThrowableCaughtMiddleware::class]` |
| `sendingResponseMiddleware` | `[]`                                                                          |
| `responseSentMiddleware`    | `[]`                                                                          |

### CLI Properties

`Valkyrja\Application\Data\CliConfig` adds two names and six middleware lists:

| Property                    | Default                                                                         |
| --------------------------- | ------------------------------------------------------------------------------- |
| `applicationName`           | `'valkyrja'` — the binary name in version and help output                       |
| `defaultCommandName`        | `'list'` — the command run when no command argument is given                    |
| `inputReceivedMiddleware`   | help, version, and global-interaction option checks                             |
| `routeMatchedMiddleware`    | `[]`                                                                            |
| `routeNotMatchedMiddleware` | `[CheckCommandForTypoMiddleware::class]`                                        |
| `routeDispatchedMiddleware` | `[]`                                                                            |
| `throwableCaughtMiddleware` | `[LogThrowableCaughtMiddleware::class, OutputThrowableCaughtMiddleware::class]` |
| `processExitingMiddleware`  | `[]`                                                                            |

## The Bootstrap Sequence

`run()` calls `App::start()`, which bootstraps the application:

```mermaid
flowchart TD
    A(["Http::run / Cli::run"]) --> B{"debugMode?"}
    B -->|true| C["Enable the default Whoops handler"]
    B -->|false| D["Define the APP_START constant"]
    C --> D
    D --> E["Set Directory::$basePath from config->dir"]
    E --> F["Create the container, create the application, set the timezone"]
    F --> G["Register the core singletons"]
    G --> H["Invoke config->callbacks with the application"]
    H --> I{"Is ContainerData already a singleton?"}
    I -->|No| J["Register every service provider,\nsnapshot the result as ContainerData"]
    I -->|Yes| K["Use the existing ContainerData singleton"]
    J --> L["container->setFromData(containerData)"]
    K --> L
    L --> M(["Container ready — the entry class dispatches"])
```

The core singletons are the config — under `ConfigContract` and under its
concrete class — plus `ContainerContract` and `ApplicationContract`. When the
config implements `CliConfigContract` or `HttpConfigContract`, the bootstrap
registers that contract as an alias too.

The `ContainerData` branch is the extension point for the callbacks. The
callbacks run before the container data loads. A callback that sets a prebuilt
`ContainerData` singleton skips the service-provider registration step. When no
callback does, `ContainerServiceProvider::publishData()` registers every
service provider from `getContainerProviders()` and snapshots the container as
`ContainerData`.

## The Provider Hierarchy

A component provider is the top-level unit, listed in `config->providers`. It
implements `Valkyrja\Application\Provider\Contract\ComponentProviderContract`,
which has five methods:

```text
config->providers[]
  └── ComponentProviderContract
        ├── getComponentProviders() → ComponentProviderContract[] (dependencies)
        ├── getContainerProviders() → ServiceProviderContract[]
        ├── getEventProviders()     → ListenerProviderContract[]
        ├── getCliProviders()       → CliRouteProviderContract[]
        └── getHttpProviders()      → HttpRouteProviderContract[]
```

**Service providers** map service ids to resolution logic in the container.
**Route providers** (CLI and HTTP) register commands and routes. **Listener
providers** register event listeners. The application collects each kind
lazily, on the first call to the matching `get*Providers()` method, and caches
the result. Nothing is instantiated at collection time — cost is paid when a
service is first requested.

**Sub-component providers**, returned by `getComponentProviders()`, declare the
components a component depends on. `Valkyrja::collectProviders()` expands the
list depth-first: it adds each sub-provider before the provider that declares
it. A component's dependencies therefore register first, and the declaring
component registers after them. List order in `config->providers` is preserved
for the top-level entries.

A typical application declares one component provider of its own:

```php
use App\Provider\AppRouteProvider;
use App\Provider\AppServiceProvider;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;

class AppComponentProvider implements ComponentProviderContract
{
    public function getComponentProviders(ApplicationContract $app): array
    {
        return [];
    }

    public function getContainerProviders(ApplicationContract $app): array
    {
        return [new AppServiceProvider()];
    }

    public function getEventProviders(ApplicationContract $app): array
    {
        return [];
    }

    public function getCliProviders(ApplicationContract $app): array
    {
        return [];
    }

    public function getHttpProviders(ApplicationContract $app): array
    {
        return [new AppRouteProvider()];
    }
}
```

## Built-in Component Providers

The framework ships four aggregators in `Valkyrja\Application\Provider`. Each
declares framework components through `getComponentProviders()` and returns
`[]` from the other four methods.

| Provider                                  | Composition                                                                                         | Default for  |
| ----------------------------------------- | --------------------------------------------------------------------------------------------------- | ------------ |
| `ApplicationComponentProvider`            | Container, Event                                                                                    | `Config`     |
| `CliApplicationComponentProvider`         | `ApplicationComponentProvider` + CLI Interaction, Middleware, Routing, Server + Log                 | —            |
| `CliWithHttpApplicationComponentProvider` | `CliApplicationComponentProvider` + HTTP Message, Middleware, Routing, RoutingCli, Server           | `CliConfig`  |
| `HttpApplicationComponentProvider`        | `ApplicationComponentProvider` + HTTP Message, Middleware, Routing, RoutingCli, Server + Log + View | `HttpConfig` |

- `ApplicationComponentProvider` is the minimal core: the container and the
  event components only.
- `CliApplicationComponentProvider` serves a pure console application with no
  HTTP surface.
- `CliWithHttpApplicationComponentProvider` serves a console application whose
  commands work with HTTP routing data.
- `HttpApplicationComponentProvider` serves a web application; it has no CLI
  components.

List an aggregator alongside your own provider:

```php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Provider\HttpApplicationComponentProvider;

new HttpConfig(providers: [
    new HttpApplicationComponentProvider(),
    new AppComponentProvider(),
]);
```

## Debug Mode

`debugMode: true` enables the Whoops throwable handler in two places:

1. `App::start()` enables a default handler first, before any other bootstrap
   step, so a bootstrap failure renders a stack trace.
2. After bootstrap, the entry class registers a `WhoopsThrowableHandler` as the
   `ThrowableHandlerContract` singleton and enables it.

Warning: Whoops output exposes internal details. Never set `debugMode: true`
in production.

## Persistent Worker Lifecycle

In a persistent worker runtime, one PHP process handles many requests. State
from one request bleeds into the next unless the framework isolates it.
`Valkyrja\Application\Entry\Abstract\WorkerHttp` implements the isolation; the
three concrete classes in the table above supply each runtime's request loop.

The invariant: **the parent application and its container are frozen after
`bootstrap()` returns.** Every request gets its own `ChildContainer` and
`ChildApplication`, and the worker discards both when the request ends.

```mermaid
flowchart TD
    A(["Worker process starts"]) --> B["bootstrap(config)"]
    B --> C["Full bootstrap sequence, throwable handler,\nbootstrapParentServices()"]
    C --> D["container->getData()\ncapture the ContainerData snapshot once"]
    D --> E(["Parent frozen — the request loop begins"])
    E --> F["The runtime delivers a request"]
    F --> G["handle(app, data, request)"]
    G --> H["New ChildContainer(parent, data)\nNew ChildApplication(app, childContainer)"]
    H --> I["Dispatch the request"]
    I --> J["Discard the child"]
    J --> E
```

`bootstrap()` takes the config and returns the application:

```php
$app = static::bootstrap($config);

$container = $app->getContainer();
$data      = $container->getData(); // captured once, reused for each request
```

`getData()` returns a `ContainerData` value object that holds the parent
container's maps. PHP arrays are copy-on-write, so each child receives a
logical copy at no cost until the child writes to a map.

`handle($app, $data, $request)` serves one request. The `ChildContainer` reads
its own maps first and falls back to the parent through `ContainerContract`. A
singleton resolved in the child stays in the child. The `ChildApplication`
returns the child container from `getContainer()` and delegates every other
method to the parent.

### Customizing the Parent

Override `bootstrapParentServices()` to force-resolve services that are
expensive to create and safe to share. A service resolved here lives in the
frozen parent; a service not resolved here is created fresh in each request's
child. The base implementation resolves the route collection:

```php
use Valkyrja\Application\Entry\FrankenPhp\FrankenPhpHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Routing\Matcher\Contract\MatcherContract;

class AppWorkerHttp extends FrankenPhpHttp
{
    public static function bootstrapParentServices(ApplicationContract $app): void
    {
        parent::bootstrapParentServices($app);

        $app->getContainer()->getSingleton(MatcherContract::class);
    }
}
```

### Child Container Variants

Two `ChildContainer` implementations exist in `Valkyrja\Container\Manager`:

- `ChildContainer` (the default) delegates to the parent through
  `ContainerContract`, so any parent that implements the contract works.
- `NativeChildContainer` reads the parent's protected fields directly for a
  lower construction cost. It requires a concrete `Container` parent. Use it
  only when profiling shows a bottleneck at child construction.

To swap the implementation, override `getChildContainer()` in your concrete
worker subclass.
