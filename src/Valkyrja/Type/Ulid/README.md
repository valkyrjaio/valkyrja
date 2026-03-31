# ULID

## Introduction

The ULID subcomponent generates and validates Universally Unique Lexicographically
Sortable Identifiers as specified by the [ULID spec](https://github.com/ulid/spec).
ULIDs are 128-bit identifiers encoded as 26-character Crockford Base32 strings.
They are URL-safe, case-insensitive, and sort lexicographically by generation time.

A ULID encodes a 48-bit millisecond timestamp in the first 10 characters followed
by 80 bits of randomness in the remaining 16 characters. When multiple ULIDs are
generated within the same millisecond, the random component is incremented
monotonically to preserve sort order.

## The UlidContract

`Valkyrja\Type\Ulid\Contract\UlidContract` extends `TypeContract<string>`:

```php
public function asValue(): string;
public function asFlatValue(): string;
```

## Value Object

```php
use Valkyrja\Type\Ulid\Ulid;

$ulid = new Ulid('01ARZ3NDEKTSV4RRFFQ69G5FAV');

// Or from an unknown value:
$ulid = Ulid::fromValue($someValue);
```

The constructor validates the string against the ULID regex and throws
`InvalidUlidException` on failure.

## Generating ULIDs

```php
use Valkyrja\Type\Ulid\Factory\UlidFactory;

// Generate from current time (millisecond precision)
$ulid = UlidFactory::generate();                    // uppercase
$ulid = UlidFactory::generate(lowerCase: true);     // lowercase
$ulid = UlidFactory::generateLowerCase();           // lowercase shorthand

// Generate from a specific datetime
$ulid = UlidFactory::generate(new DateTime('2024-01-01'));
```

Both `generate()` and `generateLowerCase()` return a plain `string`.

## Valid Characters

ULIDs use Crockford Base32 encoding, which excludes characters that are visually
ambiguous (`I`, `L`, `O`, `U`):

```
0123456789ABCDEFGHJKMNPQRSTVWXYZ
```

The `VALID_CHARACTERS` constant on `UlidFactory` holds the full set (both cases).

## Monotonicity

When multiple ULIDs are generated within the same millisecond, the factory
increments the lowest random segment rather than generating fresh randomness.
This guarantees that ULIDs generated in rapid succession remain sortable. If all
80 random bits overflow (extremely unlikely in practice), the timestamp component
is incremented by one.

## Validation

```php
use Valkyrja\Type\Ulid\Factory\UlidFactory;

UlidFactory::validate($string); // throws InvalidUlidException on failure
```

The `REGEX` constant on `UlidFactory` holds the validation pattern:
`[0-7][valid_chars]{25}` — the leading `[0-7]` constrains the maximum timestamp
to stay within 48 bits.

## Format

```
 01ARZ3NDEKTSV4RRFFQ69G5FAV
 |---------|----------------|
  10 chars    16 chars
  timestamp   randomness
```

- Total: 26 Crockford Base32 characters
- Timestamp: 48 bits (milliseconds since Unix epoch)
- Randomness: 80 bits