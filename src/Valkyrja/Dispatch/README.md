# Dispatch

## Introduction

The Dispatch component contains data objects that each describe one callable
target — a class, a method, a callable, a property, a constant, or a global
variable. The component also contains a `Dispatcher` that invokes such a
description. Reach for this component when code must store a call as data and
invoke that data later.

## The DispatcherContract

The `DispatcherContract` defines one method:

```php
public function dispatch(DispatchContract $dispatch, array $arguments = []): mixed;
```

`dispatch()` returns the value that the target produces. Arguments passed to
`dispatch()` replace the arguments stored on the dispatch. The `Dispatcher`
resolves each dependency from the container.

## ClassDispatch

Describes a class to resolve from the container. The `Dispatcher` passes the
dependencies and arguments to the container's `get()` method.

```php
$dispatch->getClass(): string;
$dispatch->withClass(string $class): static;
$dispatch->getArguments(): array;
$dispatch->withArguments(array $arguments): static;
$dispatch->getDependencies(): array;
$dispatch->withDependencies(array $dependencies): static;
```

## MethodDispatch

Describes a named method on a class. The `Dispatcher` calls an instance method
on a container-resolved instance, and a static method on the class name. The
dependencies and arguments go to the method call, not to the class resolution.

```php
// All ClassDispatch methods, plus:
$dispatch->getMethod(): string;
$dispatch->withMethod(string $method): static;
$dispatch->isStatic(): bool;
$dispatch->withIsStatic(bool $isStatic): static;
// Factory: builds a static MethodDispatch from an array callable.
MethodDispatch::fromCallableOrArray(callable|array $callable): static;
```

## CallableDispatch

Holds a raw PHP callable, which the `Dispatcher` invokes directly.

```php
$dispatch->getCallable(): callable;
$dispatch->withCallable(callable $callable): static;
$dispatch->getArguments(): array;
$dispatch->withArguments(array $arguments): static;
$dispatch->getDependencies(): array;
$dispatch->withDependencies(array $dependencies): static;
```

## PropertyDispatch

Describes a named property, instance or static, on a class. The `Dispatcher`
does not use dependencies or arguments for a property dispatch.

```php
// All ClassDispatch methods, plus:
$dispatch->getProperty(): string;
$dispatch->withProperty(string $property): static;
$dispatch->isStatic(): bool;
$dispatch->withIsStatic(bool $isStatic): static;
```

## ConstantDispatch

Describes a global constant, or a class constant when a class name is set.

```php
$dispatch->getConstant(): string;
$dispatch->withConstant(string $constant): static;
$dispatch->hasClass(): bool;
$dispatch->getClass(): string;
$dispatch->withClass(string $class): static;
$dispatch->withoutClass(): static;
```

## GlobalVariableDispatch

Describes a named entry in the PHP `$GLOBALS` array.

```php
$dispatch->getVariable(): string;
$dispatch->withVariable(string $variable): static;
```

## Serialization

Every dispatch type implements `DispatchContract`, which extends
`JsonSerializable` and `Stringable`. A closure does not encode as JSON, so a
`CallableDispatch` that holds a closure does not serialize. Use
`MethodDispatch::fromCallableOrArray()` with an array callable instead.

## Service Registration

`DispatchServiceProvider` registers the following singleton:

| Contract             | Description                   |
| :------------------- | :---------------------------- |
| `DispatcherContract` | The dispatcher implementation |
