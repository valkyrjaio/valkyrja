# Reflection

## Introduction

The Reflection component wraps PHP's native reflection API with a caching layer.
Every reflection object is created once and reused on subsequent calls, keeping
the cost of attribute collection, dependency analysis, and other
reflection-heavy operations to a minimum.

## The Reflector Contract

`Valkyrja\Reflection\Contract\ReflectorContract` provides access to all standard
reflection targets:

```php
public function forClass(string $class): ReflectionClass;
public function forClassConstant(string $class, string $const): ReflectionClassConstant;
public function forClassProperty(string $class, string $property): ReflectionProperty;
public function forClassMethod(string $class, string $method): ReflectionMethod;
public function forFunction(string $function): ReflectionFunction;
public function forClosure(Closure $closure): ReflectionFunction;
```

All results are cached. Calling `forClass('App\Models\User')` twice returns the
same `ReflectionClass` instance without recreating it.

## Dependency Extraction

Two methods extract constructor and function dependencies as a map of parameter
name to class name:

```php
public function getDependencies(ReflectionFunctionAbstract $reflection): array;
public function getDependenciesFromParameters(ReflectionParameter ...$parameters): array;
```

Both return `array<string, class-string>` — only parameters that are typed with
a class are included. Built-in types (`int`, `string`, etc.), enums, and untyped
parameters are excluded automatically.

These methods are used internally by the container's attribute collector and
anywhere the framework needs to resolve constructor arguments without explicit
configuration.

## Service Registration

The Reflection service provider registers a single singleton:

| Contract / Class    | Description                         |
|:--------------------|:------------------------------------|
| `ReflectorContract` | Caching reflection wrapper instance |