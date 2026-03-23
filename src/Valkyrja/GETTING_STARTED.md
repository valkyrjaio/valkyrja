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

This command copies the configuration example files into their properly named counterparts. These config files are intentionally excluded from version control by default — because they are environment-specific. Whether you commit them to your repository is your choice, but the power of Valkyrja's configuration model lies in the ability to define a distinct config class per environment and generate a data cache tailored to each one.

## Configuration

Valkyrja is configured via **typed PHP classes**, not `.env` files or flat array files. Your application's configuration lives in PHP objects with typed properties and sensible defaults.

After running the post-install command, you will find your config classes in the `app/` directory. The entry point for HTTP applications is an `HttpConfig` instance, and for CLI applications a `CliConfig` instance — both extending the base `Valkyrja\Application\Data\Config` class.

Because configuration is plain PHP, it can contain logic. You can read from `$_ENV`, PHP ini values, or any other source directly inside your config class. This means per-environment configuration is not a special framework feature — it is just PHP:

```php
// Example: reading environment-specific values inside a config class
public string $key = APP_KEY, // defined via PHP ini or server config
```

If most of your configuration is identical across environments, a single config class that reads a handful of environment-specific values is perfectly sufficient. If environments diverge significantly, you can maintain separate config classes and generate a data cache for each.

## Generating the Data Cache

For production deployments, Valkyrja can generate a data cache class that captures the fully resolved container state. With this cache active, the framework skips all provider registration and service binding on every request — making it faster than a micro-framework while retaining full functionality.

To generate the cache, run these commands:

```bash
# Generate the CLI component data cache
php app/bin/cli data:generate

# Generate the HTTP component data cache
php app/bin/cli http:data:generate
```

These commands are available out of the box. The framework ships with pre-registered CLI route providers (`Valkyrja\Cli\Routing\Provider\CliRouteProvider` for CLI and `Valkyrja\Http\Routing\Provider\CliRouteProvider` for HTTP) that are included in the default configuration.

Run these commands whenever your configuration changes or after deploying new code. During development, the cache is bypassed automatically when debug mode is enabled in your config.

## Directory Structure

A standard Valkyrja application is contained within the `app/` directory:

```
app/
  bin/
    cli              # CLI entry point — run as: php app/bin/cli <command>
  public/
    index.php        # HTTP entry point — pointed to by your web server
  Provider/
    Data/            # Generated data cache classes (gitignored by default)
```

The framework itself lives in your `vendor/` directory as `valkyrja/valkyrja`. Your application code — providers, controllers, commands, models — lives wherever you choose to put it, namespaced however you prefer.

## Running the Application

**For HTTP**, point your web server's document root at `app/public/`. Any web server that can serve PHP works — Nginx, Apache, FrankenPHP, or others. The `index.php` file handles all incoming requests.

**For CLI**, run commands directly:

```bash
# List all available commands (default when no command is given)
php app/bin/cli

# Run a specific command
php app/bin/cli <command-name>
```

## What Comes Next

With your application running, the next concepts worth understanding are:

- **The Application & Container** — how services are registered, resolved, and cached
- **HTTP Routing & Middleware** — how requests are matched to handlers and filtered
- **CLI Routing & Commands** — how commands are defined and dispatched
- **Event Dispatching** — how components communicate without coupling
