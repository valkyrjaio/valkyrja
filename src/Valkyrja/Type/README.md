# Type

## Introduction

The Type component provides typed value objects — wrappers around PHP values
that validate on construction and serialize cleanly. Every type implements
`TypeContract`. The component also contains a model system with property
casting, a collection, JSON wrappers, identifier types, and support for the
UUID, ULID, and VLID formats.

## TypeContract

`Valkyrja\Type\Contract\TypeContract` is the root contract for all types:

```php
public static function fromValue(mixed $value): static;    // factory
public function asValue(): mixed;                          // the wrapped value
public function asFlatValue(): string|int|float|bool|null; // flattened for storage or transport
public function modify(callable $closure): static;         // returns a new, modified instance
```

`modify()` passes the current value through the callable and returns a new
instance. Every type also implements `JsonSerializable`, so `json_encode()`
accepts a type directly.

## Primitive Wrappers

| Class              | Wraps    | Note                                                |
| :----------------- | :------- | :-------------------------------------------------- |
| `StringT`          | `string` | String operations, see below                        |
| `NonEmptyString`   | `string` | Throws `StringInvalidEmptyStringException` for `''` |
| `BoolT`            | `bool`   |                                                     |
| `TrueT`            | `true`   | The constructor takes no argument                   |
| `FalseT`           | `false`  | The constructor takes no argument                   |
| `IntT`             | `int`    |                                                     |
| `FloatT`           | `float`  |                                                     |
| `NullT`            | `null`   | The constructor takes no argument                   |
| `ArrayT`           | `array`  |                                                     |
| `NonEmptyArray`    | `array`  | Throws `ArrayInvalidNonEmptyException` for `[]`     |
| `ObjectT`          | `object` | `asFlatValue()` returns the JSON-encoded object     |
| `SerializedObject` | `object` | `asFlatValue()` returns the `serialize()` output    |

### StringT

Transformations return a new `StringT` instance: `replace()`, `replaceAll()`,
`replaceAllWith()`, `substr()`, `toUpperCase()`, `toLowerCase()`,
`toTitleCase()`, `toCapitalized()`, `toCapitalizedWords()`, `toSnakeCase()`,
`toSlug()`, `toStudlyCase()`, `ucFirstLetter()`.

Checks return `bool`: `startsWith()`, `startsWithAny()`, `endsWith()`,
`endsWithAny()`, `contains()`, `containsAny()`, `isEmail()`, `isAlphabetic()`,
`isLowercase()`, `isUppercase()`.

The length checks also return `bool` and never throw:

```php
public function min(int $min = 0): bool;   // true when the length is at least $min
public function max(int $max = 256): bool; // true when the length is at most $max
```

## Identifiers

| Class      | Contract           | Wraps         |
| :--------- | :----------------- | :------------ |
| `Id`       | `IdContract`       | `string\|int` |
| `StringId` | `StringIdContract` | `string`      |
| `IntId`    | `IntIdContract`    | `int`         |

The contracts live in `Valkyrja\Type\Id\Contract`. Each one extends
`TypeContract` directly — `StringIdContract` and `IntIdContract` do not extend
`IdContract`.

## Models

`Valkyrja\Type\Model` provides a base model class with property access, mass
assignment, array serialization, change tracking, and optional type casting.
See the [Model README](Model/README.md).

## UUID, ULID, and VLID

The component provides typed identifiers for the UUID, ULID, and VLID
(Valkyrja Universally Unique Lexicographically Sortable Identifier) formats,
each with factories, validation, and version-specific exceptions. See the
[UUID](Uuid/README.md), [ULID](Ulid/README.md), and [VLID](Vlid/README.md)
READMEs. `Valkyrja\Type\Uid` holds the shared base factory and value object.

## Collections

`Valkyrja\Type\Collection\Contract\CollectionContract` describes an
array-backed collection. Keys are `string|int`. Values are
`string|int|float|bool|array|object|null`, shown as `TValue` below:

```php
public function setAll(array $collection): static;
public function all(): array;
public function keys(): array;
public function get(string|int $key, TValue $default = null): TValue;
public function set(string|int $key, TValue $value): static;
public function has(string|int $key): bool;
public function remove(string|int $key): static;
public function exists(mixed $value): bool;
public function count(): int;
public function isEmpty(): bool;
public function __toString(): string; // the JSON-encoded collection
```

The magic methods `__get()`, `__set()`, `__isset()`, and `__unset()` mirror
`get()`, `set()`, `has()`, and `remove()`.

## JSON

`Json` wraps a decoded JSON array, and `JsonObject` wraps a decoded JSON
object. For both, `asFlatValue()` returns the JSON-encoded string. The
contracts are `JsonContract` and `JsonObjectContract` in
`Valkyrja\Type\Json\Contract`.

## Enums

`Valkyrja\Type\Enum\Contract\EnumContract` extends `TypeContract` and PHP's
`UnitEnum`. Apply the `Valkyrja\Type\Enum\Trait\Enumerable` trait to an enum to
implement it. `fromValue()` returns the matching case or throws
`EnumInvalidValueException`. `asFlatValue()` returns the backed value, or the
case name for a unit enum. `modify()` throws `EnumCannotModifyException`.
