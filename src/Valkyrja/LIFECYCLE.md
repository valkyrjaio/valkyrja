# Request Lifecycle

## Introduction

When building with any framework, confidence comes from understanding what is actually happening under the hood. Valkyrja is designed to be fast, lean, and explicit — and its lifecycle reflects those values. Nothing is loaded that hasn't been asked for, and nothing is hidden behind layers of magic.

This document walks you through the full lifecycle of both an HTTP request and a CLI command in a Valkyrja application, from the first line of code executed to the final response sent. By the time you finish reading, the framework should feel transparent rather than mysterious.

## Entry Points

A Valkyrja application has two entry points, one for each runtime environment.

**HTTP requests** are directed to `app/public/index.php` by your web server of choice (Nginx, Apache, FrankenPHP, or any other). This file is minimal by design — it hands control to the framework immediately.

**CLI commands** are invoked via `app/bin/cli`, run as `php app/bin/cli <command-name>`. If no command name is provided, the CLI component will display a list of all available commands by default (this behaviour is configurable).

Both entry points follow the same pattern: they call the `run()` method on their respective entry class — `Valkyrja\Application\Entry\Http` or `Valkyrja\Application\Entry\Cli`. These entry classes exist to eliminate the boilerplate that would otherwise be duplicated across every project, and to give the framework a single place to evolve that code without requiring developers to update their own `index.php` or `cli` files.

## Lifecycle Overview

### First Steps

The `run()` method on the entry class calls `start()`, which performs three immediate tasks:

1. Defines a `VALKYRJA_START` constant set to the current microtime. This gives you a precise reference point for benchmarking — at any stage in your application, you can compare `microtime(true)` against this constant to know exactly how long the framework has been running.
2. Sets the application's working directory (in a standard project, this is `./app`).
3. Creates the application instance.

### The Container and Application

Creating the application instance begins with the container. A new container is instantiated first, and then passed — along with the configuration you provided to `run()` — into a new `Application` instance.

The container is Valkyrja's dependency injection system and the backbone of the entire framework. Every major service, component, and object lives in and is resolved through the container. Configuration in Valkyrja is expressed as typed PHP classes — not environment strings, not flat arrays — which means your IDE understands your configuration, your static analysis tools can validate it, and your configuration is as fast as native PHP objects.

### Loading Components

With the application instantiated, Valkyrja determines whether to load components fresh or to use a pre-generated data cache class.

**Without a cache** (or when running in debug mode), the framework loads all components by iterating through the providers defined in `Valkyrja\Application\Provider\Abstract\Provider`. Each component registers its service providers, which in turn bind their services into the container. This is the standard development flow.

**With a cache**, the framework loads a pre-generated PHP class that contains the fully resolved container state from a previous run. This eliminates the cost of provider registration and service binding entirely on every request. With this cache active, Valkyrja becomes faster than SlimPHP — a micro-framework — while still providing a full-featured, robust foundation.

The cache is generated via CLI commands that ship with the framework:

- `php app/bin/cli data:generate` — generates the CLI component data cache
- `php app/bin/cli http:data:generate` — generates the HTTP component data cache

These commands are available because the framework ships with pre-baked CLI route providers — `Valkyrja\Cli\Routing\Provider\CliRouteProvider` for CLI data and `Valkyrja\Http\Routing\Provider\CliRouteProvider` for HTTP data — both of which are registered automatically through the default configuration. Developers can also register these commands individually if they prefer a more selective setup.

### HTTP: Handling the Request

Once the container is ready, the HTTP kernel takes over. It receives the incoming request and passes it through the application's **middleware pipeline** before it ever reaches a route or controller.

Middleware in Valkyrja is organised around the lifecycle of the request itself. There are distinct middleware groups for each stage:

- **Request Received** — runs immediately when the request enters the application
- **Route Matched** — runs after a matching route is found
- **Route Not Matched** — runs when no route matches the incoming request
- **Route Dispatched** — runs after the matched route's handler has executed
- **Throwable Caught** — runs if an exception is thrown at any point
- **Sending Response** — runs just before the response is sent to the client
- **Terminated** — runs after the response has been sent, for cleanup tasks

If the request passes through the matched route's middleware, the route handler or controller method executes and returns a response. That response travels back outward through the middleware stack, giving each layer the opportunity to inspect or modify it, before being sent to the client.

### CLI: Handling the Command

The CLI lifecycle mirrors the HTTP lifecycle closely, by design. The CLI kernel receives the input, passes it through its own middleware pipeline, dispatches the matched command, and returns an exit code. The middleware stages are analogous:

- **Input Received** — runs when the command input enters the application
- **Command Matched / Not Matched** — runs based on whether a matching command was found
- **Command Dispatched** — runs after the matched command has executed
- **Throwable Caught** — handles any exceptions
- **Exited** — runs after the command has exited

This symmetry between HTTP and CLI is intentional. Once you understand one, you understand both.

## Focus on Configuration

Valkyrja's configuration philosophy is worth understanding early, because it shapes everything. Rather than reading from environment variables at runtime via a flat class of string constants, Valkyrja uses **typed PHP config classes** — plain objects with typed properties and sensible defaults. You configure the framework by instantiating these classes and setting their properties, which you then pass to the `run()` method.

This approach means configuration is validated by PHP itself, understood by your IDE, analysable by tools like PHPStan and Psalm, and has zero runtime overhead beyond a native PHP object. It also makes Valkyrja's configuration portable — the same paradigm translates cleanly to other languages, because it relies on language constructs rather than framework-specific conventions.

The base config class is `Valkyrja\Application\Data\Config`, which accepts a `CliConfig` or `HttpConfig` depending on your entry point, each of which holds the configuration for their respective component trees.

## The Philosophy Behind the Lifecycle

Valkyrja is built on a simple principle: **load only what you need, and make everything fast by default**. The framework ships with a robust and extensive feature set, but activates only a lean core out of the box. Additional functionality — ORM, mail, cache, broadcast, and more — is added when you choose to include it, not bundled in whether you use it or not.

This is the inverse of the approach taken by larger frameworks. Where Laravel and Symfony start with everything and let you remove what you don't need, Valkyrja starts with a fast, minimal core and lets you add what you do. The result is a framework that is nearly three times faster than both Laravel and Symfony without any caching, and faster than SlimPHP with its data cache active — while still providing the structure and tooling of a full-featured framework.

Understanding this philosophy makes the lifecycle make sense. Every step described above exists because it earns its place.
