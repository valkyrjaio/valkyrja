# Throwable

## Introduction

The Throwable component provides Valkyrja's exception hierarchy and error
handler. It defines a base exception contract that adds trace code generation to
PHP's native `Throwable` interface, and ships with a Whoops-based handler that
renders detailed error output for development.

## The Throwable Contract

`Valkyrja\Throwable\Contract\Throwable` extends PHP's native `Throwable` and
adds one method:

```php
public function getTraceCode(): string;
```

A trace code is a deterministic identifier derived from the exception class name
and its stack trace. It is useful for correlating log entries to specific
failure points without exposing raw stack traces to end users. The concrete
implementation generates an MD5 hash:

```php
md5($throwable::class . $throwable->getTraceAsString())
```

## Exception Classes

The component provides a standard exception hierarchy under
`Valkyrja\Throwable\Exception\`:

- `Exception` — base exception, extends PHP's `\Exception` and implements
  `Throwable`
- `RuntimeException` — for errors that arise at runtime
- `InvalidArgumentException` — for invalid arguments passed to methods
- `TypeError` — for type-related errors

These classes serve as the base types from which every other component in the
framework derives its own exceptions, ensuring all framework exceptions carry a
trace code and conform to a consistent hierarchy.

## The Throwable Handler

`Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract` defines the
interface for registering a global error and exception handler:

```php
public static function enable(int $errorReportingLevel = E_ALL, bool $displayErrors = false): void;
public static function getTraceCode(Throwable $throwable): string;
```

`enable()` registers the handler with PHP's error and exception handling system.
`getTraceCode()` is a static utility that generates the trace code for any
throwable without requiring an instance.

### Whoops Integration

`WhoopsThrowableHandler` integrates with
the [Whoops](https://github.com/filp/whoops) library to produce human-readable
error output during development.

When enabled:

- **Browser requests** receive a full HTML stack trace page with
  syntax-highlighted source context, request data, and environment information.
- **AJAX requests** (detected via the `X-Requested-With` header) receive a
  structured JSON response containing the full trace.

Valkyrja installs `WhoopsThrowableHandler` automatically when `debugMode: true`
is set in your application configuration. In production, `debugMode` should
always be `false` — Whoops output may expose internals you do not want visible
to users.