# ULID

## Introduction

The ULID subcomponent generates and validates Universally Unique
Lexicographically Sortable Identifiers per the
[ULID spec](https://github.com/ulid/spec). A ULID is 26 Crockford Base32
characters: a 10-character millisecond timestamp followed by 16 characters of
randomness. ULIDs are URL-safe and sort lexicographically by generation time.

```
01ARZ3NDEKTSV4RRFFQ69G5FAV
|--------||--------------|
 timestamp    randomness
 10 chars      16 chars
```

## Value Object

`Ulid` implements `TypeContract<string>` through `UlidContract` (`asValue()`
and `asFlatValue()` both return `string`):

```php
use Valkyrja\Type\Ulid\Ulid;

$ulid = new Ulid();                             // self-generates a new ULID
$ulid = new Ulid('01ARZ3NDEKTSV4RRFFQ69G5FAV'); // validates the given string
```

The constructor validates a given string and throws `InvalidUlidException` on
failure. `fromValue()` throws `UlidInvalidFromValueException` when the value
is not a string; `null` triggers generation.

## Generating ULIDs

Both factory methods return a plain `string`:

```php
use Valkyrja\Type\Ulid\Factory\UlidFactory;

$ulid = UlidFactory::generate();                        // current time, uppercase
$ulid = UlidFactory::generate(lowerCase: true);         // lowercase
$ulid = UlidFactory::generateLowerCase();               // lowercase shorthand
$ulid = UlidFactory::generate(new DateTime('2024-01-01')); // a specific datetime
```

## Monotonicity

When the factory generates again within the same millisecond, it increments
the previous random value instead of drawing fresh randomness, so the
identifiers stay sorted. When every random part is at its maximum, the factory
increments the timestamp by one.

## Valid Characters and Validation

Crockford Base32 excludes the ambiguous characters `I`, `L`, `O`, and `U`:

```
0123456789ABCDEFGHJKMNPQRSTVWXYZ
```

The `VALID_CHARACTERS` constant on `UlidFactory` holds the set in both cases.
The `REGEX` constant is `[0-7][valid_chars]{25}` — the leading `[0-7]` keeps
the timestamp within 48 bits:

```php
use Valkyrja\Type\Ulid\Factory\UlidFactory;

UlidFactory::validate($string); // throws InvalidUlidException on failure
```
