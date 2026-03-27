# Getting Started

## Installation

The fastest way to start a new Valkyrja project is via Composer's `create-project` command:

```bash
composer create-project valkyrja/application your-project
cd your-project
```

This clones the application skeleton and installs all dependencies. Once complete, run the post-install setup command:

```bash
composer post-root-package-install
```

This copies the configuration example files into their working counterparts. These config files are environment-specific and are excluded from version control by default — whether you commit them is your choice. The power of Valkyrja's configuration model lies in being able to maintain a distinct, typed config class per environment and generate a data cache tailored to each one.

## Configuration

Valkyrja is configured through **typed PHP classes**, not `.env` files or flat array files. Your application's configuration lives in PHP objects with typed constructor parameters and sensible defaults.

After installation, you will find your config classes in the `app/` directory. HTTP applications use an `HttpConfig` instance, and CLI applications use a `CliConfig` instance — both extending the base `Valkyrja\Application\Data\Config` class.

Because configuration is plain PHP, it can contain any logic you need. Reading from environment variables, PHP ini values, deployment secrets, or computed values is straightforward:

```php
use Valkyrja\Application\Data\HttpConfig;

new HttpConfig(
    dir:         dirname(__DIR__),
    environment: $_ENV['APP_ENV'] ?? 'production',
    debugMode:   ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
    key:         $_ENV['APP_KEY'] ?? '',
);
```

If most of your configuration is shared across environments, a single config class reading a handful of environment-specific values is sufficient. If environments diverge significantly, maintain separate config classes and generate a distinct data cache for each.

## The Data Cache

For production deployments, Valkyrja can generate a data cache class that captures the fully resolved container and routing state from your configuration. With this cache active, the framework skips all provider registration, service binding, and route collection on every request — the container is populated in a single step from the pre-generated file.

Generate the cache after any deployment that changes configuration or adds new routes or services:

```bash
# Generate the CLI component data cache
php app/bin/cli data:generate

# Generate the HTTP component data cache
php app/bin/cli http:data:generate
```

These commands are available out of the box. The framework ships with pre-registered CLI route providers (`Valkyrja\Cli\Routing\Provider\CliRouteProvider` for CLI and `Valkyrja\Http\Routing\Provider\CliRouteProvider` for HTTP) that are included in the default component providers.

The cache is bypassed automatically during development — set `debugMode: true` in your config and the framework always loads fresh from providers, reflecting code changes immediately.

## Directory Structure

A standard Valkyrja application is contained within the `app/` directory:

```
app/
  bin/
    cli                  # CLI entry point
  public/
    index.php            # HTTP entry point
  Provider/
    Data/                # Generated data cache classes (gitignored by default)
```

The framework itself lives in `vendor/valkyrja/valkyrja`. Your application code — providers, controllers, commands, events — lives wherever you choose, namespaced however you prefer. Valkyrja imposes no directory structure on your own code.

## Running the Application

**For HTTP**, point your web server's document root at `app/public/`. Any web server that can serve PHP works — Nginx, Apache, FrankenPHP, Caddy. The `index.php` file handles all incoming requests via a single entry point.

**For CLI**, invoke commands directly:

```bash
# List all available commands (default when no command name is given)
php app/bin/cli

# Run a specific command
php app/bin/cli <command-name>

# Get help for a specific command
php app/bin/cli help <command-name>
```

## Debug Mode

Setting `debugMode: true` in your configuration has two effects: the data cache is bypassed entirely (components always load fresh from providers), and Valkyrja installs a detailed throwable handler via [Whoops](https://github.com/filp/whoops) that renders full stack traces in the browser or terminal.

Never run `debugMode: true` in production. Beyond the performance difference, the error output may expose internals you do not want visible to users.

## What Comes Next

With your application running, the concepts worth understanding in order are:

- **The Application** — how the bootstrap sequence works and how components are loaded
- **The Container** — how services are registered, resolved, and cached
- **HTTP Routing & Middleware** — how requests are matched to handlers and filtered through the pipeline
- **CLI Routing & Commands** — how commands are defined, parameterised, and dispatched
- **Event Dispatching** — how components communicate without direct coupling
