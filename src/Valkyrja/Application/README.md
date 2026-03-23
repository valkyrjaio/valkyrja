# The Application

## Introduction

The `Application` class is the central object that ties a Valkyrja project
together. It holds the container, carries the configuration, coordinates
component loading, and exposes the runtime environment to the rest of the
framework. Most of the time you will interact with the application indirectly —
through the container, through providers, through the HTTP or CLI kernels — but
understanding what it does and how it is constructed clarifies everything that
comes after.

## Entry Classes

You rarely instantiate the `Application` directly. Instead, the entry classes
handle this for you:

- `Valkyrja\Application\Entry\Http` — for web applications
- `Valkyrja\Application\Entry\Cli` — for console applications

Both entry classes expose a `run()` method that accepts your configuration
object and handles the full lifecycle from bootstrap to response. They exist to
eliminate boilerplate — the code that would otherwise be duplicated in every
project's `index.php` and `bin/cli` files is maintained inside the framework,
meaning improvements and changes to the boot sequence propagate to all projects
without requiring manual updates.

## Bootstrapping

When `run()` is called, it delegates to `start()`, which performs the following
steps in order:

**1. Start time.** The constant `VALKYRJA_START` is defined as the current
microtime. This gives your application a precise reference point for
benchmarking at any stage of execution.

**2. Working directory.** The current working directory is set to your
application's root (by default, `./app`). This ensures that file paths resolved
relative to the application root behave consistently regardless of where PHP was
invoked from.

**3. Container creation.** A new container instance is created.

**4. Application instantiation.** The container and your configuration object
are passed to a new `Application` instance.

**5. Component loading.** The application determines whether to load components
fresh or use the data cache (see below).

## Configuration

The application is configured via a typed PHP config object — either
`Valkyrja\Application\Data\HttpConfig` for HTTP applications or
`Valkyrja\Application\Data\CliConfig` for CLI applications. Both extend the base
`Valkyrja\Application\Data\Config` class, which carries settings common to all
application types:

| Property        | Default                 | Description                                              |
|-----------------|-------------------------|----------------------------------------------------------|
| `namespace`     | `'App'`                 | Your application's root namespace                        |
| `dir`           | current directory       | The application's root directory                         |
| `version`       | framework version       | Your application version string                          |
| `environment`   | `'production'`          | The current environment name                             |
| `debugMode`     | `false`                 | Disables the data cache and enables verbose error output |
| `timezone`      | `'UTC'`                 | PHP's default timezone                                   |
| `key`           | `'some_secret_app_key'` | Application secret key — **change this**                 |
| `dataPath`      | `'App/Provider/Data'`   | Path where generated data cache classes are written      |
| `dataNamespace` | `'App\\Provider\\Data'` | Namespace for generated data cache classes               |
| `providers`     | framework defaults      | The list of component providers to load                  |
| `callbacks`     | `[]`                    | Callbacks invoked after the application is bootstrapped  |

The `CliConfig` and `HttpConfig` subclasses add their own properties relevant to
their respective runtimes.

Because configuration is plain PHP, any property can be populated from any
source — environment variables, PHP ini values, constants, or logic. The
framework imposes no constraints on how values arrive at the config object.

## Component Loading and the Data Cache

After the application is instantiated, it loads components. This is where
Valkyrja's performance model becomes tangible.

### Without the Data Cache

When no data cache exists — or when `debugMode` is `true` — the application
iterates through the component providers listed in `providers`, loads each one,
and registers all child service providers, route providers, and event providers
into the container and routing tables. This is the standard development flow.

### With the Data Cache

When a data cache class exists and `debugMode` is `false`, the application loads
the pre-generated class directly, bypassing provider registration entirely. The
container is populated in a single step with no iteration, no provider
instantiation, and no service binding logic. This is what makes Valkyrja faster
than a micro-framework in production.

The cache is generated against your specific configuration, meaning each
environment can have its own cache precisely tuned to its own providers and
settings. Regenerate the cache whenever configuration changes or new code is
deployed:

```bash
php app/bin/cli data:generate        # CLI data cache
php app/bin/cli http:data:generate   # HTTP data cache
```

## The Provider Hierarchy

Component loading follows a deliberate hierarchy. Understanding it makes the
system predictable.

**Component Providers** are registered in your config's `providers` array. They
are the top-level unit — each one represents a logical component of your
application (your own app code, a third-party package, a framework component). A
component provider can optionally implement `PublishableProviderContract` to
define a `publish()` method that runs on every boot, cached or not.

**Service Providers**, **CLI Route Providers**, **HTTP Route Providers**, and *
*Event Listener Providers** live inside component providers. They are deferred —
their publish callbacks are only invoked when the services or routes they
declare are actually needed.

The key rule: **anything that can be deferred should be deferred.** The
`publish()` method on a component provider runs unconditionally and should
contain only what genuinely must happen on every single request. Binding
services, registering routes, or adding event listeners in `publish()` defeats
the caching mechanism entirely.

## The Application Lifecycle in Context

For an HTTP request, the full sequence looks like this:

```
index.php
  └── Http::run(HttpConfig $config)
        └── start()
              ├── Define VALKYRJA_START
              ├── Set working directory
              ├── Create container
              ├── Create Application(container, config)
              └── Load components (cache or providers)
                    └── HTTP kernel receives request
                          ├── Middleware pipeline
                          ├── Route matching
                          ├── Handler dispatch
                          ├── Middleware pipeline (outbound)
                          └── Send response
```

For a CLI command, the structure is identical up to component loading — at which
point the CLI kernel takes over instead of the HTTP kernel.

## Accessing the Application

Within a service provider's publish callback, the container is available as the
first argument. The application instance itself is registered in the container
and can be retrieved when needed:

```php
$app = $container->getSingleton(ApplicationContract::class);
```

In practice, most code should depend on specific services rather than on the
application object directly. The application is a framework-level concern; your
business logic should interact with the container, the router, the event
dispatcher, and other specific services — not the application itself.

## Debug Mode

Setting `debugMode: true` in your config has two effects:

1. The data cache is bypassed entirely — components are always loaded fresh from
   providers.
2. The application surfaces more detailed error information.

Never run with `debugMode: true` in production. The performance difference is
significant and the error output may expose internals you do not want visible to
users.
