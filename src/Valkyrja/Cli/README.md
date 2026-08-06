# CLI

The CLI component maps command-line input to command methods and writes the
result to the terminal. The component mirrors the structure of the HTTP
component, with three renamed concepts:

| HTTP             | CLI            |
| ---------------- | -------------- |
| `Request`        | `Input`        |
| `Response`       | `Output`       |
| `RequestHandler` | `InputHandler` |

## Configuration

`CliConfig` configures a CLI application. Two properties are CLI-specific:

- **`applicationName`** — the binary name shown in help and version output.
- **`defaultCommandName`** — the command that runs when the input names no
  command. The default is `list`.

```php
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Entry\Cli;

Cli::run(new CliConfig(
    dir:                __DIR__,
    applicationName:    'myapp',
    defaultCommandName: 'list',
));
```

### Global middleware

`CliConfig` holds one array per middleware stage: `inputReceivedMiddleware`,
`routeMatchedMiddleware`, `routeNotMatchedMiddleware`,
`routeDispatchedMiddleware`, `throwableCaughtMiddleware`, and
`processExitingMiddleware`. Middleware in these arrays runs on every
invocation. Three of the arrays ship with defaults:

| `CliConfig` array           | Default classes                                                                                                                                                                               |
| --------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `inputReceivedMiddleware`   | `CheckForHelpOptionsMiddleware` (`--help`/`-h`), `CheckForVersionOptionsMiddleware` (`--version`/`-v`), `CheckGlobalInteractionOptionsMiddleware` (`--quiet`, `--silent`, `--no-interaction`) |
| `routeNotMatchedMiddleware` | `CheckCommandForTypoMiddleware` (suggests a similar command name)                                                                                                                             |
| `throwableCaughtMiddleware` | `LogThrowableCaughtMiddleware`, `OutputThrowableCaughtMiddleware`                                                                                                                             |

The other three arrays default to empty.

## Entry Point

`Cli::run()` boots the application, parses `$_SERVER['argv']` into an `Input`
with `InputFactory::fromGlobals()`, resolves the `InputHandlerContract` from
the container, and calls `InputHandler::run()`. The handler runs the
middleware pipeline, writes the output messages, and calls `Exiter::exit()`
with the output's exit code.

## Routing

### Route Providers

A route provider implements `CliRouteProviderContract`. The contract declares
two instance methods. `getControllerClasses()` returns classes that carry
`#[Route]` attributes. `getRoutes()` returns pre-built `RouteContract`
objects. The framework's own provider registers the built-in commands:

```php
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;

class CliRoutingCliRouteProvider implements CliRouteProviderContract
{
    public function getControllerClasses(): array
    {
        return [
            HelpCommand::class,
            ListBashCommand::class,
            ListCommand::class,
            VersionCommand::class,
        ];
    }

    public function getRoutes(): array
    {
        return [];
    }
}
```

A component provider returns route providers from its `getCliProviders()`
method, and `CliConfig`'s `providers` array lists the component providers. At
boot, the framework passes each controller class to the
`AttributeRouteCollector`, which reflects the class and converts its
`#[Route]` attributes into routes in the `RouteCollection`.

There is no compiled route file, and there is no separate matcher class. The
`Router` queries the `RouteCollection` by command name, and `CliRoutingData`
holds the collected routes in memory as closures.

### Attribute Registration

Annotate a controller method with `#[Route]` to register a command. The
attribute is repeatable — stack it to serve several command names from one
method:

```php
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Cli\Routing\Provider\CliRoutingCliRouteProvider;

class ListCommand
{
    #[Route(name: 'list', description: 'List all commands')]
    #[RouteHandler([CliRoutingCliRouteProvider::class, 'listHandler'])]
    public function run(): OutputContract
    {
        // php myapp list
    }
}
```

### Route Handlers

Every route has a handler with this signature:

```php
callable(ContainerContract $container, RouteContract $route): OutputContract
```

The `Router` calls the handler with the container and the matched route after
the `RouteMatched` middleware has run. Wire the handler with the
`#[RouteHandler]` attribute, or pass `handler:` to `#[Route]` directly. The
handler resolves the command class from the container and calls it:

```php
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Container\Manager\Contract\ContainerContract;

public static function listHandler(ContainerContract $container, RouteContract $route): OutputContract
{
    return $container->getSingleton(ListCommand::class)->run();
}
```

The `Router` also registers the matched route in the container as
`RouteContract`, so a command class can receive the route through its
constructor. The route carries the parsed argument and option values. Read
them by parameter name:

```php
$commandName = $route->getOption('command')->getFirstValue();
$appName     = $route->getArgument('applicationName')->getFirstValue();
```

### Arguments

Arguments are positional values. Declare them as `ArgumentParameter` objects
in the `arguments` array of `#[Route]`, or as repeatable attributes on the
method:

```php
use Valkyrja\Cli\Routing\Attribute\ArgumentParameter;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;

#[Route(name: 'list:bash', description: 'List all commands for bash completion')]
#[ArgumentParameter(
    name:        'applicationName',
    description: 'The application name',
    mode:        ArgumentMode::REQUIRED,
)]
public function run(): OutputContract
{
    // ...
}
```

| Enum                | Cases                  |
| ------------------- | ---------------------- |
| `ArgumentMode`      | `REQUIRED`, `OPTIONAL` |
| `ArgumentValueMode` | `DEFAULT`, `ARRAY`     |

An `ARRAY` argument collects all remaining positional values, so it must be
the last declared argument.

### Options

Options are named flags. `OptionParameter` requires a `name` and a
`description`; `shortNames` takes an array, and `defaultValue` sets the
fallback value:

```php
use Valkyrja\Cli\Routing\Attribute\OptionParameter;
use Valkyrja\Cli\Routing\Attribute\Route;

#[Route(name: 'list', description: 'List all commands')]
#[OptionParameter(
    name:        'namespace',
    description: 'An optional namespace to filter commands by',
    shortNames:  ['n'],
)]
public function run(): OutputContract
{
    // ...
}
```

| Enum              | Cases                      |
| ----------------- | -------------------------- |
| `OptionMode`      | `REQUIRED`, `OPTIONAL`     |
| `OptionValueMode` | `NONE`, `DEFAULT`, `ARRAY` |

A `NONE` option takes no value. An `ARRAY` option accepts repeated flags. The
constructor also accepts `valueDisplayName` (shown in help output),
`validValues` (allowed values), and `cast` (a type cast for the values).

### Help Text

`#[Route]` accepts a `helpText` callable that returns a `MessageContract`.
The `help` command renders it:

```php
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;

public static function help(): MessageContract
{
    return new Message('A command to get help for a specific command.');
}
```

## Input and Output

### Input

`InputContract` is the parsed invocation. `getArguments()` returns
`ArgumentContract[]` in positional order. `getOption()` returns
`OptionContract[]`, because a flag can repeat:

```php
$name    = $input->getCommandName();      // 'user:create'
$args    = $input->getArguments();        // ArgumentContract[]
$formats = $input->getOption('format');   // OptionContract[]
$verbose = $input->hasOption('verbose');  // bool
```

### Output

A command method returns an `OutputContract`, which carries messages and an
exit code. Create one through the `OutputFactoryContract`:

```php
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;

return $this->outputFactory
    ->createOutput(exitCode: ExitCode::SUCCESS)
    ->withMessages(new SuccessMessage('Done.'));
```

| Output class   | Writes to                 |
| -------------- | ------------------------- |
| `PlainOutput`  | stdout                    |
| `FileOutput`   | a file                    |
| `StreamOutput` | a PHP stream resource     |
| `EmptyOutput`  | nowhere (discards output) |

| Message class    | Purpose                          |
| ---------------- | -------------------------------- |
| `Message`        | Plain text                       |
| `SuccessMessage` | Success-styled text              |
| `ErrorMessage`   | Error-styled text                |
| `WarningMessage` | Warning-styled text              |
| `Banner`         | A wrapped block around a message |
| `NewLine`        | A blank line                     |

### Exit Codes

`ExitCode` follows the BSD sysexits convention. `InputHandler::run()` passes
the code's integer value to `Exiter::exit()` after the `ProcessExiting`
middleware runs:

| Case             | Value | Meaning                           |
| ---------------- | ----- | --------------------------------- |
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

## Middleware

Each stage has a contract, and a middleware class implements the contracts
that apply to it. A middleware either forwards the call through the handler
chain or short-circuits with an `OutputContract`:

```php
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;

class MaintenanceModeMiddleware implements InputReceivedMiddlewareContract
{
    public function __construct(
        protected OutputFactoryContract $outputFactory,
        protected bool $isDown = false,
    ) {
    }

    public function inputReceived(
        InputContract $input,
        InputReceivedHandlerContract $handler
    ): InputContract|OutputContract {
        if ($this->isDown) {
            return $this->outputFactory
                ->createOutput(exitCode: ExitCode::UNAVAILABLE)
                ->withMessages(new ErrorMessage('The application is down.'));
        }

        return $handler->inputReceived($input);
    }
}
```

Every stage accepts global middleware through its `CliConfig` array. Four
stages also accept per-route middleware through the matching `#[Route]`
parameter, or through the repeatable `#[Middleware]` attribute:

| Stage             | Contract                            | When it fires                                | Per-route |
| ----------------- | ----------------------------------- | -------------------------------------------- | --------- |
| `InputReceived`   | `InputReceivedMiddlewareContract`   | Before route matching                        | No        |
| `RouteMatched`    | `RouteMatchedMiddlewareContract`    | After match, before dispatch                 | Yes       |
| `RouteNotMatched` | `RouteNotMatchedMiddlewareContract` | When no command matches                      | No        |
| `RouteDispatched` | `RouteDispatchedMiddlewareContract` | After dispatch                               | Yes       |
| `ThrowableCaught` | `ThrowableCaughtMiddlewareContract` | When a throwable is caught                   | Yes       |
| `ProcessExiting`  | `ProcessExitingMiddlewareContract`  | After output is written, before process exit | Yes       |

If no `ThrowableCaught` middleware changes the output, the `InputHandler`
falls back to a default error banner with the command name and the exception
message.

## Built-In Commands

| Command     | Description                                           |
| ----------- | ----------------------------------------------------- |
| `list`      | Lists all registered commands with their descriptions |
| `list:bash` | Outputs a bash-completion-compatible command list     |
| `help`      | Displays help text for a given command                |
| `version`   | Displays the application version                      |
| `http:list` | Lists all registered HTTP routes (HTTP component)     |

The HTTP routing component registers `http:list` through its own provider,
`Valkyrja\Http\Routing\Provider\HttpRoutingCliRouteProvider`.

## Lifecycle

```mermaid
flowchart TD
    A([Cli::run]) --> B[Bootstrap - build Input from argv]
    B --> C[Stage 1 - InputReceived]
    C -->|"short-circuit"| H[Write output to stdout]
    C -->|throwable| J[Stage 5 - ThrowableCaught]
    C --> D{"Router: command matched?"}
    D -->|"no match"| E["Stage 3 - RouteNotMatched (error output)"]
    D -->|matched| F[Stage 2 - RouteMatched]
    E -->|throwable| J
    E --> H
    F -->|"short-circuit"| H
    F -->|throwable| J
    F --> G["Route handler: handler(container, route)"]
    G -->|throwable| J
    G --> I[Stage 4 - RouteDispatched]
    I -->|throwable| J
    I --> H
    J --> H
    H --> K[Stage 6 - ProcessExiting]
    K --> L["Exiter::exit(ExitCode)"]
    L --> M([Process ends])
```
