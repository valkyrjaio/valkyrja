# Log

## Introduction

The Log component provides a [PSR-3](https://www.php-fig.org/psr/psr-3/)
compliant logging service. `LoggerContract` extends `Psr\Log\LoggerInterface`
directly, meaning the Valkyrja logger works anywhere a PSR-3 logger is expected.
The default backend is [Monolog](https://github.com/Seldaek/monolog) writing to
a daily rotating file. A null implementation is included for testing.

## The Logger Contract

`Valkyrja\Log\Contract\LoggerContract` extends `Psr\Log\LoggerInterface` and
adds one method:

```php
// PSR-3 methods (inherited)
public function debug(string|Stringable $message, array $context = []): void;
public function info(string|Stringable $message, array $context = []): void;
public function notice(string|Stringable $message, array $context = []): void;
public function warning(string|Stringable $message, array $context = []): void;
public function error(string|Stringable $message, array $context = []): void;
public function critical(string|Stringable $message, array $context = []): void;
public function alert(string|Stringable $message, array $context = []): void;
public function emergency(string|Stringable $message, array $context = []): void;
public function log(mixed $level, string|Stringable $message, array $context = []): void;

// Valkyrja addition
public function throwable(Throwable $throwable, string|Stringable $message, array $context = []): void;
```

`throwable()` logs an exception alongside a message, automatically including the
throwable in the context. Use it anywhere you catch an exception and want to
record it without manually building the context array.

## Log Levels

`Valkyrja\Log\Enum\LogLevel` is a string-backed enum covering all PSR-3 levels:

| Case        | Value         |
|:------------|:--------------|
| `DEBUG`     | `'debug'`     |
| `INFO`      | `'info'`      |
| `NOTICE`    | `'notice'`    |
| `WARNING`   | `'warning'`   |
| `ERROR`     | `'error'`     |
| `CRITICAL`  | `'critical'`  |
| `ALERT`     | `'alert'`     |
| `EMERGENCY` | `'emergency'` |

## Implementations

| Class        | Description                                                         |
|:-------------|:--------------------------------------------------------------------|
| `PsrLogger`  | Adapter that wraps any `Psr\Log\LoggerInterface` (default: Monolog) |
| `NullLogger` | No-op; all log calls are silently discarded                         |

The active implementation is resolved from the container as `LoggerContract`.
The `PsrLogger` implementation delegates to the `Psr\Log\LoggerInterface`
singleton, which is pre-configured as a Monolog instance writing to
`storage/logs/valkyrja-YYYY-MM-DD.log`.

## Configuration

| Env Constant         | Default            | Description                              |
|:---------------------|:-------------------|:-----------------------------------------|
| `LOG_DEFAULT_LOGGER` | `PsrLogger::class` | Implementation bound to `LoggerContract` |

## Service Registration

The Log service provider registers the following singletons:

| Contract / Class          | Description                                    |
|:--------------------------|:-----------------------------------------------|
| `LoggerContract`          | Active logger (default: `PsrLogger`)           |
| `PsrLogger`               | PSR-3 adapter wrapping Monolog                 |
| `NullLogger`              | No-op implementation                           |
| `Psr\Log\LoggerInterface` | Monolog `Logger` instance with `StreamHandler` |