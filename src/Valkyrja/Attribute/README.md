# Attribute

## Introduction

The Attribute component collects PHP 8 attributes from classes, methods,
properties, constants, parameters, functions, and closures. It uses the
Reflection component internally and returns instantiated attribute objects,
optionally filtered by attribute type.

This is the component that makes `#[Route]`, `#[Listener]`, and every other
Valkyrja attribute work — route providers and event providers pass their
controller and listener classes through the collector to discover and build
their routing and listener maps.

## The Collector Contract

`Valkyrja\Attribute\Collector\Contract\CollectorContract` covers every
reflection target:

```php
// Class-level
public function forClass(string $class, ?string $attribute = null, ?int $flags = null): array;
public function forClassMembers(string $class, ?string $attribute = null, ?int $flags = null): array;
public function forClassAndMembers(string $class, ?string $attribute = null, ?int $flags = null): array;

// Constants
public function forConstant(string $class, string $constant, ?string $attribute = null, ?int $flags = null): array;
public function forConstants(string $class, ?string $attribute = null, ?int $flags = null): array;

// Properties
public function forProperty(string $class, string $property, ?string $attribute = null, ?int $flags = null): array;
public function forProperties(string $class, ?string $attribute = null, ?int $flags = null): array;

// Methods
public function forMethod(string $class, string $method, ?string $attribute = null, ?int $flags = null): array;
public function forMethods(string $class, ?string $attribute = null, ?int $flags = null): array;

// Method parameters
public function forMethodParameters(string $class, string $method, ?string $attribute = null, ?int $flags = null): array;
public function forMethodParameter(string $class, string $method, string $parameter, ?string $attribute = null, ?int $flags = null): array;

// Functions and closures
public function forFunction(string $function, ?string $attribute = null, ?int $flags = null): array;
public function forFunctionParameters(string $function, ?string $attribute = null, ?int $flags = null): array;
public function forClosure(Closure $closure, ?string $attribute = null, ?int $flags = null): array;
public function forClosureParameters(Closure $closure, ?string $attribute = null, ?int $flags = null): array;
```

The `$attribute` parameter filters results to only instances of the given
class (or its descendants, using the default
`ReflectionAttribute::IS_INSTANCEOF` flag). Pass `null` to collect all
attributes.

All methods return an array of instantiated attribute objects — not
`ReflectionAttribute` instances.

## Reflection-Aware Attributes

Attributes that need access to the reflection context they were found on can
implement `Valkyrja\Attribute\Contract\ReflectionAwareAttributeContract`:

```php
public function getReflection(): Reflector;
public function setReflection(Reflector $reflection): void;
```

The collector calls `setReflection()` automatically on any collected attribute
that implements this contract, passing the reflection object the attribute was
read from. Apply the `ReflectionAwareAttribute` trait for a ready-made
implementation.

## Service Registration

The Attribute service provider registers a single singleton:

| Contract / Class    | Description                                       |
|:--------------------|:--------------------------------------------------|
| `CollectorContract` | Attribute collector backed by `ReflectorContract` |