# Type

## Introduction

The Type component provides a library of typed value objects — wrappers around
PHP's primitive and compound types that enforce type constraints, provide
domain-specific operations, and serialize cleanly. Every type in the component
implements `TypeContract`, a generic interface that defines a consistent
factory, accessor, and transformation API.

The component also includes a model system with property casting, a collection
type, JSON wrappers, identifier types, and support for UUIDs, VLIDs, and ULIDs.

## TypeContract

`Valkyrja\Type\Contract\TypeContract` is the root interface for all types:

```php
public static function fromValue(mixed $value): static;  // factory
public function asValue(): mixed;                         // retrieve the wrapped value
public function asFlatValue(): string|int|float|bool|null; // serialise for storage or transport
public function modify(callable $closure): static;        // immutable transformation
```

`modify()` accepts a callable, passes the current value through it, and returns
a new instance — preserving immutability. Types also implement
`JsonSerializable`, so they can be passed directly to `json_encode()`.

## Primitive Wrappers

Each primitive type has a corresponding wrapper class.

| Class     | Wraps  | `asValue()` return |
|:----------|:-------|:-------------------|
| `StringT` | string | `string`           |
| `BoolT`   | bool   | `bool`             |
| `IntT`    | int    | `int`              |
| `FloatT`  | float  | `float`            |
| `NullT`   | null   | `null`             |
| `ObjectT` | object | `object`           |
| `ArrayT`  | array  | `array`            |

### StringT

`StringT` provides a broad set of string operations, all of which return a new
`StringT` instance:

**Transformation** — `replace()`, `substr()`, `toUpperCase()`, `toLowerCase()`,
`toTitleCase()`, `toSnakeCase()`, `toSlug()`, `toStudlyCase()`

**Validation** — `startsWith()`, `endsWith()`, `contains()`, `isEmail()`,
`isAlphabetic()`, `isLowercase()`, `isUppercase()`

**Constraints** — `min(int $length)`, `max(int $length)` — assert minimum or
maximum length, throwing on violation

## Identifiers

Three identifier types wrap values used as entity keys:

| Class      | Wraps         |
|:-----------|:--------------|
| `Id`       | `string\|int` |
| `StringId` | `string`      |
| `IntId`    | `int`         |

All implement `Valkyrja\Type\Id\Contract\IdContract`, which extends
`TypeContract` with `asValue(): string|int`.

## UUIDs, VLIDs, and ULIDs

The component provides strongly-typed identifiers for UUID, VLID (Valkyrja
Lexicographically sortable ID), and ULID formats. Each format has a base
contract and version-specific contracts and implementations:

- **UUID** — versions 1, 3, 4, 5, 6, 7, 8 (`Valkyrja\Type\Uuid\`)
- **VLID** — versions 1, 2, 3, 4 (`Valkyrja\Type\Vlid\`)
- **ULID** — `Valkyrja\Type\Ulid\`
- **UID** — `Valkyrja\Type\Uid\`

All extend `TypeContract` with `asValue(): string` and `asFlatValue(): string`.
Invalid values throw version-specific exceptions (e.g.
`InvalidUuidV4Exception`).

## Models

`Valkyrja\Type\Model\Contract\ModelContract` defines the base model contract.
`CastableModelContract` extends it with automatic property casting on mass
assignment:

```php
public function getCastings(): array; // returns property => Cast mappings
```

`Cast` specifies the type class to cast a property to when it is set from an
array (for example, from a database row or request body). `ArrayCast` handles
properties that should be arrays of typed values.

```php
use Valkyrja\Type\Model\Data\Cast;
use Valkyrja\Type\Model\Data\ArrayCast;

class UserModel extends CastableModel
{
    public function getCastings(): array
    {
        return [
            'id'    => new Cast(IntId::class),
            'email' => new Cast(StringT::class),
            'tags'  => new ArrayCast(StringT::class),
        ];
    }
}
```

When the model is populated from an array, each property named in
`getCastings()` is automatically converted to its declared type.

## Collections

`Valkyrja\Type\Collection\Contract\CollectionContract` provides a generic,
array-backed collection:

```php
public function setAll(array $items): void;
public function all(): array;
public function get(mixed $key, mixed $default = null): mixed;
public function set(mixed $key, mixed $value): void;
public function has(mixed $key): bool;
public function remove(mixed $key): void;
public function exists(mixed $value): bool;
public function count(): int;
public function isEmpty(): bool;
```

Property access is also available via `__get()`, `__set()`, `__isset()`, and
`__unset()`.

## JSON

`Valkyrja\Type\Json\Contract\JsonContract` wraps a decoded JSON payload:

```php
public function asValue(): array;       // decoded PHP array
public function asFlatValue(): string;  // JSON-encoded string
```

`JsonObjectContract` mirrors this for JSON objects. Both implement
`TypeContract` and can be used wherever a type-safe JSON container is needed.

## Enums

`Valkyrja\Type\Enum\Contract\EnumContract` extends both `TypeContract` and PHP's
`UnitEnum`, giving enums the full `TypeContract` API:

```php
public function asValue(): static;           // the enum case itself
public function asFlatValue(): string|int;   // backed value, or name if unit enum
```

Apply the `Valkyrja\Type\Enum\Trait\Enumerable` trait to any enum to gain
`fromValue()` (creates a case from a string or int) and `asFlatValue()`. The
`modify()` method is intentionally disabled on enums — they are immutable by
nature.