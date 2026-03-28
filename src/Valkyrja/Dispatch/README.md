# The Dispatcher

## Introduction

The Dispatcher is one of the most foundational components in Valkyrja. It is the
engine that powers event dispatching, CLI command execution, and HTTP route
handling — any situation where the framework needs to invoke a callable, a
method on a class, or a class itself in response to something that has occurred.

Understanding the Dispatcher makes the entire framework more legible.

## What the Dispatcher Does

At its core, the Dispatcher resolves and invokes a **dispatch** — a description
of what to call and how to call it. Rather than calling code directly, Valkyrja
describes the call as a data object and hands it to the Dispatcher, which
handles resolution and invocation. This indirection is what makes deferred
loading, caching, and the data class generation system possible.

## The DispatcherContract

`Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract` defines a single
method:

```php
public function dispatch(DispatchContract $dispatch, array $arguments = []): mixed;
```

All dispatch types implement `DispatchContract`, which extends both
`JsonSerializable`
and `Stringable`.

## Dispatch Types

Valkyrja supports six dispatch types, each describing a different kind of
callable target:

### ClassDispatch

Resolves a class from the container directly. The dispatcher will automatically
combine the dependencies and arguments and pass them to the container get
method. Dependencies are other services that should exist in the container and
are automatically retrieved before passing to the get method. Used when the
entire class represents a single unit of work.

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
Supports both instance and static methods. In this case the arguments and
dependencies are tied to the method, not the class. The dependencies and
arguments are passed to the method that is called. This is important to note as
this means the class MUST exist in the container and cannot be lazily created
with the dispatch object automatically with its required dependencies and
arguments.

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
and invokes it directly. The dependencies, like in class and method dispatching,
are auto resolved by the container and are a list of services that should be
retrieved from the container before passing to the callable.

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
both instance and static properties. In this case dependencies and arguments are
not used at all, so the same caution regarding a class needing to exist in the
container applies to property dispatches like it does to method dispatches.

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
- **CLI** — When a command is matched, the Dispatcher invokes the route's
  dispatch.
- **HTTP** — When a route is matched, the Dispatcher invokes the route's
  dispatch.

The pattern is identical in each context. Only the argument passed and the
surrounding lifecycle differ.

## Return Values

The Dispatcher returns whatever the invoked dispatch returns. In the context of
events, return values can be collected by the event if it implements
`DispatchCollectableContract`. In CLI and HTTP contexts, the return value
becomes the response or output that the respective router passes back to the
handler, and subsequent stepped middleware.

## Why This Design

Describing calls as data objects rather than executing them directly enables the
framework's caching system. When the data cache is generated, the full set of
dispatch descriptions — for every event listener, every command, every route —
is captured in a generated PHP class. On subsequent requests, the framework
loads that class directly and the Dispatcher can invoke any handler without any
registration overhead.

> Note: Handlers may be added in the future for direct callables to be used
> instead, but this feature is currently not supported and so dispatches becomes
> the way to handle a listener call or route call at this time.

This is a significant part of why Valkyrja is fast. The Dispatcher does very
little work per request when the cache is warm — it simply receives a dispatch
description and executes it.

> Note: Handlers in the future would be even faster, but may not allow for data
> caching if closures are used instead of direct callables in the form of arrays
> like the service provider `publishers` method for example.

## Service Registration

The Dispatch service provider registers the following singletons:

| Contract / Class     | Description                   |
|:---------------------|:------------------------------|
| `DispatcherContract` | The dispatcher implementation |
