# Request Lifecycle

## Introduction

When building with any framework, understanding what happens under the hood turns debugging from guesswork into diagnosis, and architectural decisions from instinct into intention. Valkyrja is designed to be fast, lean, and transparent — and its lifecycle reflects those values directly. Every step exists for a reason, and nothing is hidden.

This document walks through the full lifecycle of both an HTTP request and a CLI command in a Valkyrja application, from the first line of code executed to the final response sent or process exited.

## Entry Points

Every Valkyrja application has one of two entry points depending on its runtime.

**HTTP** — your web server points to `app/public/index.php`. This file is intentionally minimal: it constructs your configuration object and calls `run()`:

```php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Http;

Http::run(new HttpConfig(
    dir: dirname(__DIR__),
));
```

**CLI** — commands are invoked via `php app/bin/cli <command-name>`. The `cli` file follows the same pattern:

```php
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Entry\Cli;

Cli::run(new CliConfig(
    dir: dirname(__DIR__),
    applicationName: 'myapp',
));
```

Both entry classes — `Valkyrja\Application\Entry\Http` and `Valkyrja\Application\Entry\Cli` — exist to give the framework one canonical place to evolve the bootstrap sequence. Your entry point files call `run()` and are done.

## The Bootstrap Sequence

`run()` delegates to `start()`, which performs three steps before anything else happens:

**1. Start time.** The constant `APP_START` is defined as the current microtime. You can reference this anywhere in your application to measure elapsed time from the first instruction.

**2. Working directory.** The application's base path is set to the `dir` value from your configuration. All framework path resolution uses this as its root, so file lookups behave consistently regardless of where the PHP process was invoked from.

**3. Application instantiation.** A new container is created. The container, along with your configuration object, is passed to a new `Valkyrja\Application\Kernel\Valkyrja` instance. This instance is the application — it holds the container, exposes the configuration, and coordinates component loading.

Several core singletons are immediately registered into the container: `Env`, `Config`, the concrete config class (e.g. `HttpConfig`), `ContainerContract`, and `ApplicationContract` itself. If a `CliConfig` is in use, its embedded `HttpConfig` is also registered.

## Loading Components

With the application instantiated, Valkyrja determines how to populate the container.

### Without the Data Cache

When `debugMode` is `true` (or no data cache exists), the application iterates through the component providers listed in your configuration's `providers` array. Each component provider is asked for its container service providers, which are registered into the container's deferred service map. Routes and events are loaded the same way via `getHttpProviders()`, `getCliProviders()`, and `getEventProviders()`.

No services are instantiated at this stage — the container only builds a map of what exists and how to create it. Services are resolved lazily, on first access.

### With the Data Cache

In production, a pre-generated PHP data class captures the fully resolved container state. When this file exists and `debugMode` is `false`, the framework loads it directly — no provider iteration, no service binding logic, no reflection. The container is populated in a single step.

This is what makes Valkyrja faster than a micro-framework in production. Generate the cache after any deployment:

```bash
php app/bin/cli data:generate        # CLI routing data
php app/bin/cli http:data:generate   # HTTP routing data
```

## HTTP: Handling the Request

Once the container is ready, `Http::run()` resolves the `RequestHandlerContract` from the container, builds a `ServerRequest` from PHP's superglobals via `RequestFactory::fromGlobals()`, and calls `RequestHandler::run($request)`.

The request then passes through a **seven-stage middleware pipeline**:

### Stage 1 — Request Received

Before any routing occurs. Global middleware runs here — maintenance mode checks, rate limiting, full-response cache lookups. Middleware at this stage can either return a modified request (to continue) or return a response directly (short-circuiting all remaining stages).

### Stage 2 — Route Matched

After the `Matcher` finds a matching route, before the handler is dispatched. Per-route middleware runs here — authentication, authorization, tenant resolution. Can short-circuit with a response.

### Stage 3 — Route Not Matched

When no route matches the request. A default 404 response is produced; global middleware at this stage can replace it with a custom not-found page or fallback handler.

### Stage 4 — Route Dispatched

After the matched route's controller method has executed and returned a response. Per-route middleware here handles post-dispatch concerns: adding headers, transforming response bodies, logging.

### Stage 5 — Throwable Caught

When any `Throwable` is caught during request handling. Receives the throwable alongside a default error response. Per-route and global middleware here handles error reporting and custom error responses.

### Stage 6 — Sending Response

After the response is finalised, before it is written to the output. Per-route middleware here handles final modifications: CORS headers, response compression, cache-control headers.

### Stage 7 — Terminated

After the response has been sent to the client. Work done here is invisible to the user. The appropriate stage for deferred side effects: writing logs, dispatching queued events, cache writes. The `CacheResponseMiddleware` saves successful responses to disk at this stage, making future identical requests instantaneous.

After `Terminated` middleware completes, the process finishes. Sessions are closed, FastCGI or Litespeed finish-request hooks are called if available.

## CLI: Handling the Command

Once the container is ready, `Cli::run()` resolves the `InputHandlerContract` from the container, builds an `Input` object from `$_SERVER['argv']` via `InputFactory::fromGlobals()`, and calls `InputHandler::run($input)`.

The input passes through a **six-stage middleware pipeline** that mirrors HTTP exactly:

| HTTP Stage          | CLI Equivalent      | Description                                   |
|---------------------|---------------------|-----------------------------------------------|
| `RequestReceived`   | `InputReceived`     | Before routing; can short-circuit with output |
| `RouteMatched`      | `RouteMatched`      | After match; can short-circuit with output    |
| `RouteNotMatched`   | `RouteNotMatched`   | When no command matches                       |
| `RouteDispatched`   | `RouteDispatched`   | After dispatch                                |
| `ThrowableCaught`   | `ThrowableCaught`   | When a throwable is caught                    |
| `Terminated`        | `Exited`            | After output is written; before process exits |

After `Exited` middleware completes, `InputHandler` writes the output's messages to stdout and calls `Exiter::exit()` with the `ExitCode` integer value from the output object.

## Focus on Configuration

Valkyrja's configuration philosophy is worth internalising early because it shapes everything. Rather than reading from environment variables via a flat map of string constants, Valkyrja uses **typed PHP config classes** — plain objects with typed constructor parameters and sensible defaults.

You pass a config object to `run()` and that is your application's entire configuration. It can contain logic. It can read from `$_ENV`, PHP ini values, deployment secrets, or anything else. Its properties are typed, IDE-visible, and statically analysable. There is no indirection, no magic, and no runtime cost beyond a native PHP object.

The base class is `Valkyrja\Application\Data\Config`. `HttpConfig` and `CliConfig` extend it, adding runtime-specific properties. See [The Application](Application/README.md) for a full reference.

## Lifecycle at a Glance

```
index.php / bin/cli
  └── Http::run(HttpConfig) / Cli::run(CliConfig)
        └── App::start()
              ├── Define APP_START
              ├── Set base path (config->dir)
              ├── Create Container
              ├── Create Valkyrja(container, config)
              └── Load components
                    ├── [production] Load data cache class
                    └── [development] Iterate providers → build deferred service map
                          └── HTTP / CLI kernel
                                ├── Build Request / Input from globals
                                ├── Stage 1: RequestReceived / InputReceived
                                ├── Route matching
                                ├── Stage 2: RouteMatched  (or Stage 3: RouteNotMatched)
                                ├── Dispatcher → controller method
                                ├── Stage 4: RouteDispatched
                                ├── [on throw] Stage 5: ThrowableCaught
                                ├── Stage 6: SendingResponse  [HTTP only]
                                ├── Send response / write output
                                └── Stage 7: Terminated / Exited
```
