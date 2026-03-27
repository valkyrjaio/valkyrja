# CLI

## Introduction

Valkyrja's CLI layer mirrors the HTTP layer in structure and philosophy. Where
HTTP maps requests to controller methods, CLI maps input tokens to command
methods. Where HTTP has a `RequestHandler`, CLI has an `InputHandler`. The same
Dispatcher that resolves HTTP routes and fires events resolves CLI commands, and
the middleware pipeline follows the same chain-of-responsibility pattern.

This design means that concepts you already understand from the HTTP layer —
route providers, attribute-based registration, per-route middleware — carry over
directly. The terminology shifts slightly: `Request` becomes `Input`, `Response`
becomes `Output`, and `Terminated` becomes `Exited`. Everything else is
structurally equivalent.

## Configuration

CLI applications are bootstrapped using `CliConfig`:

```php
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Entry\Cli;

Cli::run(new CliConfig(
    namespace:          'App',
    dir:                __DIR__,
    environment:        'production',
    debugMode:          false,
    timezone:           'UTC',
    key:                'your-application-key',
    dataPath:           'App/Provider/Data',
    dataNamespace:      'App\\Provider\\Data',
    applicationName:    'myapp',
    defaultCommandName: 'list',
));
```

Two properties are specific to `CliConfig`:

- **`applicationName`** — The name shown in version and help output. This is
  typically the binary name (e.g. `myapp`, `cli`).
- **`defaultCommandName`** — The command invoked when no command name is given
  on the command line. Defaults to `list`.

`CliConfig` also carries an embedded `HttpConfig` instance (`$config->http`) for
applications that run CLI and HTTP side-by-side and need HTTP services available
from CLI commands.

## Entry Point

`Cli::run()` is the single entry point for a CLI application. It boots the
application, resolves the `InputHandlerContract` from the container, and creates
an `Input` from `$_SERVER['argv']`:

```php
// cli (your binary)
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Entry\Cli;

Cli::run(new CliConfig(
    dir:             dirname(__DIR__),
    applicationName: 'myapp',
));
```

Everything that follows — middleware, route matching, dispatch, output writing,
and process exit — is managed by `InputHandler`.

## Routing

### Route Providers

Commands are registered through **route providers** — classes that implement the
CLI `ProviderContract` and return a list of controller classes and/or pre-built
route objects. The framework iterates over all registered providers during
bootstrap to build the command collection.

```php
use Valkyrja\Cli\Routing\Provider\Abstract\Provider;

class AppCommandProvider extends Provider
{
    public static function getControllerClasses(): array
    {
        return [
            UserCommands::class,
            DatabaseCommands::class,
            MakeCommands::class,
        ];
    }
}
```

When `getControllerClasses()` returns classes, the framework's
`AttributeCollector` reflects on each class, extracts `#[Route]` attributes from
its methods, and adds the resulting routes to the collection. When `getRoutes()`
returns `Route` objects directly, those are processed and added as well.

Route providers are wired into the application through a component provider's
`getCliProviders()` method. The component provider itself is listed in
`CliConfig`'s `providers` array, following the same hierarchy used for container
and event providers.

### Attribute-Based Registration

The idiomatic way to define CLI commands is to annotate controller methods with
the `#[Route]` attribute:

```php
use Valkyrja\Cli\Routing\Attribute\Route;

class UserCommands
{
    #[Route(name: 'user:create', description: 'Create a new user')]
    public function create(): OutputContract
    {
        // php myapp user:create
    }

    #[Route(name: 'user:list', description: 'List all users')]
    public function list(): OutputContract
    {
        // php myapp user:list
    }
}
```

The `#[Route]` attribute is repeatable — a single method can serve multiple
command names by stacking attributes.

### Route Parameters

CLI routes support two kinds of parameters: **arguments** (positional) and *
*options** (named flags). Both are defined via companion attribute classes and
are attached directly to the `#[Route]` declaration.

#### Arguments

Arguments are positional values passed on the command line. Declare them as
`ArgumentParameter` objects in the `arguments` array of `#[Route]`:

```php
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\ArgumentParameter;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;

#[Route(
    name: 'user:show',
    description: 'Show a user by ID',
    arguments: [
        new ArgumentParameter(
            name:        'id',
            mode:        ArgumentMode::REQUIRED,
            valueMode:   ArgumentValueMode::DEFAULT,
            description: 'The user ID',
        ),
    ]
)]
public function show(InputContract $input): OutputContract
{
    $id = $input->getArgument('id');
    // ...
}
```

`ArgumentMode` controls whether the argument is required or optional.
`ArgumentValueMode::ARRAY` allows the argument to accept multiple values.

| Enum                | Cases                  |
|---------------------|------------------------|
| `ArgumentMode`      | `REQUIRED`, `OPTIONAL` |
| `ArgumentValueMode` | `DEFAULT`, `ARRAY`     |

#### Options

Options are named flags, typically prefixed with `--`. Declare them as
`OptionParameter` objects in the `options` array of `#[Route]`:

```php
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\OptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;

#[Route(
    name: 'user:export',
    description: 'Export users to a file',
    options: [
        new OptionParameter(
            name:        'format',
            shortName:   'f',
            mode:        OptionMode::OPTIONAL,
            valueMode:   OptionValueMode::DEFAULT,
            default:     'json',
            description: 'Output format (json, csv)',
        ),
        new OptionParameter(
            name:      'verbose',
            shortName: 'v',
            mode:      OptionMode::OPTIONAL,
            valueMode: OptionValueMode::NONE,
        ),
    ]
)]
public function export(InputContract $input): OutputContract
{
    $format  = $input->getOption('format');
    $verbose = $input->getOption('verbose');
    // ...
}
```

`OptionValueMode::NONE` means the flag takes no value (it is either present or
absent). `OptionValueMode::ARRAY` accepts the option multiple times and collects
values into an array.

| Enum              | Cases                      |
|-------------------|----------------------------|
| `OptionMode`      | `REQUIRED`, `OPTIONAL`     |
| `OptionValueMode` | `NONE`, `DEFAULT`, `ARRAY` |

### Help Text

The `#[Route]` attribute accepts a `helpText` callable that returns a
`MessageContract`. This is rendered when a user calls `help <command>`:

```php
use Valkyrja\Cli\Interaction\Message\Message;

#[Route(
    name:        'db:migrate',
    description: 'Run pending database migrations',
    helpText:    fn() => new Message(
        'Run all pending migrations. Use --dry-run to preview changes without applying them.'
    ),
)]
public function migrate(InputContract $input): OutputContract { ... }
```

### Command Data Generation

Like HTTP routes, CLI routes can be compiled into a generated PHP data class for
zero-reflection production performance:

```bash
php myapp data:generate
```

The generated class is written to the path defined by `dataPath` and
`dataNamespace` in your configuration and is loaded automatically when
`debugMode` is `false`.

The HTTP routing component ships its own pair of CLI commands — `http:list` and
`http:data:generate` — registered via the
`Http\Routing\Provider\CliRouteProvider`.

## Input and Output

### Input

`InputContract` represents the parsed command-line invocation. It exposes the
command name, positional arguments, and named options:

```php
$commandName = $input->getCommandName();   // 'user:create'
$id          = $input->getArgument('id');
$format      = $input->getOption('format');
$verbose     = $input->getOption('verbose') !== null;
```

`Input` is created at the entry point via `InputFactory::fromGlobals()`, which
parses `$_SERVER['argv']` into a structured object.

### Output

`OutputContract` carries the messages that will be written to stdout and the
exit code that will be passed to `exit()`. Your command methods return an
`OutputContract`:

```php
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;

public function create(InputContract $input): OutputContract
{
    $user = $this->users->create($input->getArgument('name'));

    return $this->outputFactory
        ->createOutput()
        ->withMessages(
            new SuccessMessage('User created: ' . $user->name),
        );
}
```

Output implementations:

| Class          | Description                       |
|----------------|-----------------------------------|
| `PlainOutput`  | Standard stdout output            |
| `FileOutput`   | Writes to a file                  |
| `StreamOutput` | Writes to any PHP stream resource |
| `EmptyOutput`  | Discards all output               |

### Messages

The output message system is composable. Common message types:

| Class            | Description                               |
|------------------|-------------------------------------------|
| `Message`        | Plain text                                |
| `SuccessMessage` | Success-styled text (typically green)     |
| `ErrorMessage`   | Error-styled text (typically red)         |
| `WarningMessage` | Warning-styled text (typically yellow)    |
| `Banner`         | Wrapped block, useful for section headers |
| `NewLine`        | A blank line                              |

### Exit Codes

`OutputContract` carries an `ExitCode` enum value. `InputHandler::run()` calls
`Exiter::exit()` with the integer value of that code after the `Exited`
middleware has run. Valkyrja's exit codes follow the BSD SysExits convention:

| Case             | Value | Meaning                           |
|------------------|-------|-----------------------------------|
| `SUCCESS`        | 0     | Normal exit                       |
| `ERROR`          | 1     | Generic error                     |
| `USAGE_ERROR`    | 64    | Command used incorrectly          |
| `DATA_ERROR`     | 65    | Bad input data                    |
| `NO_INPUT`       | 67    | Input not found                   |
| `NO_USER`        | 68    | User does not exist               |
| `UNAVAILABLE`    | 69    | Service unavailable               |
| `SOFTWARE_ERROR` | 70    | Internal software error           |
| `OS_ERROR`       | 71    | Operating system error            |
| `OS_FILE_ERROR`  | 72    | OS file error                     |
| `CANT_CREATE`    | 73    | Cannot create output file         |
| `IO_ERROR`       | 74    | I/O error                         |
| `TEMP_FAIL`      | 75    | Temporary failure, user may retry |
| `PROTOCOL_ERROR` | 76    | Remote error in protocol          |
| `NO_PERMISSION`  | 77    | Permission denied                 |
| `CONFIG_ERROR`   | 78    | Configuration error               |
| `AUTO_EXIT`      | 255   | Reserved                          |

## The Middleware Pipeline

Every CLI invocation passes through a six-stage middleware pipeline. The
structure is identical to HTTP — each stage has a contract, middleware classes
implement whatever contracts apply to them, and the handler chain propagates the
call forward.

### Stage 1 — InputReceived

`InputReceivedMiddlewareContract` fires the moment input enters the handler,
before any route matching occurs. It receives the parsed `InputContract` and can
either return a modified input (to continue) or return an `OutputContract`
directly (short-circuiting the pipeline):

```php
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;

class EnvironmentCheckMiddleware implements InputReceivedMiddlewareContract
{
    public function inputReceived(
        InputContract $input,
        InputReceivedHandlerContract $handler
    ): InputContract|OutputContract {
        if (! $this->isConfigured()) {
            return $this->outputFactory
                ->createOutput(exitCode: ExitCode::CONFIG_ERROR)
                ->withMessages(new ErrorMessage('Application is not configured.'));
        }

        return $handler->inputReceived($input);
    }
}
```

`InputReceived` middleware is global — it runs on every invocation regardless of
which command is matched. Configure it in `InputHandler`.

### Stage 2 — RouteMatched

`RouteMatchedMiddlewareContract` fires after a command has been matched but
before its handler is dispatched. It receives both the input and the matched
`RouteContract`, and can return a modified route or short-circuit with an
output:

```php
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;

class ProductionGuardMiddleware implements RouteMatchedMiddlewareContract
{
    public function routeMatched(
        InputContract $input,
        RouteContract $route,
        RouteMatchedHandlerContract $handler
    ): RouteContract|OutputContract {
        if ($this->isDestructive($route) && $this->isProduction()) {
            return $this->outputFactory
                ->createOutput(exitCode: ExitCode::NO_PERMISSION)
                ->withMessages(new ErrorMessage('Cannot run destructive commands in production.'));
        }

        return $handler->routeMatched($input, $route);
    }
}
```

Declared per-route via `routeMatchedMiddleware` in `#[Route]`.

### Stage 3 — RouteNotMatched

`RouteNotMatchedMiddlewareContract` fires when the router cannot find a command
matching the input. It receives the input and a default error output and is the
right place to suggest similar commands or display a helpful error:

```php
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;

class UnknownCommandMiddleware implements RouteNotMatchedMiddlewareContract
{
    public function routeNotMatched(
        InputContract $input,
        OutputContract $output,
        RouteNotMatchedHandlerContract $handler
    ): OutputContract {
        $name = $input->getCommandName();

        return $this->outputFactory
            ->createOutput(exitCode: ExitCode::USAGE_ERROR)
            ->withMessages(
                new ErrorMessage("Unknown command: {$name}"),
                new NewLine(),
                new Message('Run `myapp list` to see available commands.'),
            );
    }
}
```

`RouteNotMatched` middleware is global — it applies to all unrecognised
commands.

### Stage 4 — RouteDispatched

`RouteDispatchedMiddlewareContract` fires after the command's handler has been
called and an output has been produced. It receives the input, the output, and
the matched route. Use it for post-dispatch concerns: logging command execution,
transforming output, appending timing information:

```php
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;

class CommandAuditMiddleware implements RouteDispatchedMiddlewareContract
{
    public function routeDispatched(
        InputContract $input,
        OutputContract $output,
        RouteContract $route,
        RouteDispatchedHandlerContract $handler
    ): OutputContract {
        $this->audit->log($input->getCommandName());

        return $handler->routeDispatched($input, $output, $route);
    }
}
```

Declared per-route via `routeDispatchedMiddleware` in `#[Route]`.

### Stage 5 — ThrowableCaught

`ThrowableCaughtMiddlewareContract` fires when any `Throwable` is caught during
command handling. It receives the input, a default error output, and the
throwable:

```php
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;

class CliErrorReportingMiddleware implements ThrowableCaughtMiddlewareContract
{
    public function throwableCaught(
        InputContract $input,
        OutputContract $output,
        Throwable $throwable,
        ThrowableCaughtHandlerContract $handler
    ): OutputContract {
        $this->logger->error($throwable->getMessage(), ['exception' => $throwable]);

        return $handler->throwableCaught($input, $output, $throwable);
    }
}
```

Declared per-route via `throwableCaughtMiddleware` in `#[Route]`, or globally in
`InputHandler`.

If no `ThrowableCaught` middleware is registered, the `InputHandler` falls back
to a default error output that displays the command name and the exception
message in a styled banner.

### Stage 6 — Exited

`ExitedMiddlewareContract` fires after the output has been written and just
before `Exiter::exit()` is called with the exit code. It is the appropriate
stage for deferred cleanup: closing database connections, flushing queued
events, writing metrics:

```php
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;

class CleanupMiddleware implements ExitedMiddlewareContract
{
    public function exited(
        InputContract $input,
        OutputContract $output,
        ExitedHandlerContract $handler
    ): void {
        $this->flushPendingEvents();
        $this->closeConnections();

        $handler->exited($input, $output);
    }
}
```

Declared per-route via `exitedMiddleware` in `#[Route]`, or globally in
`InputHandler`.

### Pipeline Summary

| Stage             | When it fires                                 | Can short-circuit | Scope     |
|-------------------|-----------------------------------------------|-------------------|-----------|
| `InputReceived`   | Before route matching                         | Yes               | Global    |
| `RouteMatched`    | After match, before dispatch                  | Yes               | Per-route |
| `RouteNotMatched` | When no command matches                       | No                | Global    |
| `RouteDispatched` | After dispatch                                | No                | Per-route |
| `ThrowableCaught` | When a throwable is caught                    | No                | Per-route |
| `Exited`          | After output is written, before process exits | No                | Per-route |

## Built-In Commands

The framework registers several built-in commands via its own route providers.
These are available in any application that includes the relevant component
providers:

| Command              | Description                                           |
|----------------------|-------------------------------------------------------|
| `list`               | Lists all registered commands with their descriptions |
| `list:bash`          | Outputs a bash-completion-compatible command list     |
| `help`               | Displays help text for a given command                |
| `-v` / `version`     | Displays the application and framework version        |
| `data:generate`      | Compiles CLI routes to a generated PHP data class     |
| `http:list`          | Lists all registered HTTP routes (HTTP component)     |
| `http:data:generate` | Compiles HTTP routes to a generated PHP data class    |

## Full Input Lifecycle

From `Cli::run()` to process exit, the lifecycle is:

1. `CliConfig` is validated and the application is bootstrapped.
2. Component providers register services into the container.
3. Route providers register commands into the collection (or load the compiled
   data file).
4. `InputFactory::fromGlobals()` parses `$_SERVER['argv']` into an
   `InputContract`.
5. `InputHandler::run()` is called.
6. `InputReceived` middleware runs (environment checks, global guards).
7. The `Router` asks the `Matcher` to find a matching command.
8. **If no command matches**: `RouteNotMatched` middleware runs and produces an
   error output.
9. **If a command matches**: `RouteMatched` middleware runs (access control,
   production guards).
10. The `Dispatcher` calls the matched controller method, injecting dependencies
    from the container.
11. `RouteDispatched` middleware runs (auditing, output transformation).
12. **If a throwable is caught** at any point: `ThrowableCaught` middleware
    runs.
13. The output's messages are written to stdout.
14. `Exited` middleware runs (deferred cleanup).
15. `Exiter::exit()` is called with the `ExitCode` integer value from the
    output.
