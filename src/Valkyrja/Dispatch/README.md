# The Dispatcher

## Introduction

The Dispatcher is one of the most foundational components in Valkyrja. It is the
engine that powers event dispatching, CLI command execution, and HTTP route
handling — any situation where the framework needs to invoke a callable, a
method on a class, or a class itself in response to something that has occurred.

Understanding the Dispatcher makes the entire framework more legible. Once you
see how it works in the context of events, you will immediately recognise the
same pattern operating in the CLI and HTTP layers.

## What the Dispatcher Does

At its core, the Dispatcher resolves and invokes a **dispatch** — a description
of what to call and how to call it. Rather than calling code directly, Valkyrja
describes the call as a data object and hands it to the Dispatcher, which
handles resolution and invocation. This indirection is what makes deferred
loading, caching, and the data class generation system possible.

## The DispatcherContract

`Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract` defines a single method:

```php
public function dispatch(DispatchContract $dispatch, array $arguments = []): mixed;
```
All dispatch types implement `DispatchContract`, which extends both `JsonSerializable`
and `Stringable`. Serialisability is what allows the full set of dispatches —
every event listener, command, and route handler — to be written to a generated
PHP class as part of the data cache.

## Dispatch Types

Valkyrja supports six dispatch types, each describing a different kind of
callable target:

### ClassDispatch

Resolves a class from the container and invokes it directly as an invokable
(i.e., calls `__invoke`). Used when the entire class represents a single unit of
work.

```php
$dispatch->getClass(): string;
$dispatch->withClass(string $class): static;
$dispatch->getArguments(): array;
$dispatch->withArguments(array $arguments): static;
$dispatch->getDependencies(): array;
$dispatch->withDependencies(array $dependencies): static;
```

### MethodDispatch

Resolves a class from the container and invokes a specific named method on it.
Supports both instance and static methods.

```php
// All ClassDispatch methods, plus:
$dispatch->getMethod(): string;
$dispatch->withMethod(string $method): static;
$dispatch->isStatic(): bool;
$dispatch->withIsStatic(bool $isStatic): static;

// Factory helper:
MethodDispatch::fromCallableOrArray(callable|array $callable): static;
```

### CallableDispatch

Holds a raw PHP callable (closure, function name, or invokable object reference)
and invokes it directly.

```php
$dispatch->getCallable(): callable;
$dispatch->withCallable(callable $callable): static;
$dispatch->getArguments(): array;
$dispatch->withArguments(array $arguments): static;
$dispatch->getDependencies(): array;
$dispatch->withDependencies(array $dependencies): static;
```

### PropertyDispatch

Resolves a class from the container and reads a named property on it. Supports
both instance and static properties.

```php
// All ClassDispatch methods, plus:
$dispatch->getProperty(): string;
$dispatch->withProperty(string $property): static;
$dispatch->isStatic(): bool;
$dispatch->withIsStatic(bool $isStatic): static;
```

### ConstantDispatch

Reads a named global constant, or a class constant when a class name is also
provided.

```php
$dispatch->getConstant(): string;
$dispatch->withConstant(string $constant): static;
$dispatch->hasClass(): bool;
$dispatch->getClass(): string;
$dispatch->withClass(string $class): static;
$dispatch->withoutClass(): static;
```

### GlobalVariableDispatch

Reads a named entry from the PHP `$GLOBALS` array.

```php
$dispatch->getVariable(): string;
$dispatch->withVariable(string $variable): static;
```

## How It Connects to Events, CLI, and HTTP

The same Dispatcher underpins all three runtime contexts:

- **Events** — When an event is fired, the Dispatcher invokes each registered
  listener's dispatch, passing the event object as the argument.
- **CLI** — When a command is matched, the Dispatcher invokes the command
  handler's dispatch, passing the parsed input.
- **HTTP** — When a route is matched, the Dispatcher invokes the route handler's
  dispatch, passing the request.

The pattern is identical in each context. Only the argument passed and the
surrounding lifecycle differ.

## Return Values

The Dispatcher returns whatever the invoked dispatch returns. In the context of
events, return values can be collected by the event if it implements
`DispatchCollectableContract`. In CLI and HTTP contexts, the return value
becomes the response or output that the kernel sends back to the caller.

## Why This Design

Describing calls as data objects rather than executing them directly enables the
framework's caching system. When the data cache is generated, the full set of
dispatch descriptions — for every event listener, every command, every route —
is captured in a generated PHP class. On subsequent requests, the framework
loads that class directly and the Dispatcher can invoke any handler without any
registration overhead.

This is a significant part of why Valkyrja is fast. The Dispatcher does very
little work per request when the cache is warm — it simply receives a dispatch
description and executes it.

## Service Registration

The Dispatch service provider registers the following singletons:

| Contract / Class     | Description                    |
|:---------------------|:-------------------------------|
| `DispatcherContract` | The dispatcher implementation  |