# VLID

## Introduction

The VLID subcomponent generates and validates Valkyrja Universally Unique
Lexicographically Sortable Identifiers — a Valkyrja-specific extension of the
ULID format designed for higher time precision and controlled randomness.

The key difference from ULID is **microsecond** timestamp precision rather than
millisecond. VLID encodes the current time down to the microsecond, trades a
small amount of randomness for that extra precision, and remains 128 bits total.
Four versions (V1–V4) are provided, each with a different balance of random bits.
The version digit is embedded directly in the identifier itself at position 14.

## The VlidContract

`Valkyrja\Type\Vlid\Contract\VlidContract` extends `TypeContract<string>`:

```php
public function asValue(): string;
public function asFlatValue(): string;
```

Each version has its own contract (`VlidV1Contract` through `VlidV4Contract`)
extending `VlidContract`.

## Value Objects

```php
use Valkyrja\Type\Vlid\Vlid;
use Valkyrja\Type\Vlid\VlidV1;

$vlid = new Vlid('01ARZ3NDEKTSV14RRFFQ69G5F');
$v1   = new VlidV1('01ARZ3NDEKTSV14RRFFQ69G5F');

// Or from an unknown value:
$vlid = Vlid::fromValue($someValue);
```

| Class    | Contract          |
|:---------|:------------------|
| `Vlid`   | `VlidContract`    |
| `VlidV1` | `VlidV1Contract`  |
| `VlidV2` | `VlidV2Contract`  |
| `VlidV3` | `VlidV3Contract`  |
| `VlidV4` | `VlidV4Contract`  |

## Generating VLIDs

All generation goes through `VlidFactory` or a version-specific factory:

```php
use Valkyrja\Type\Vlid\Factory\VlidFactory;

// Generate from current time (microsecond precision)
$v1 = VlidFactory::v1();
$v2 = VlidFactory::v2();
$v3 = VlidFactory::v3();
$v4 = VlidFactory::v4();

// Generate lowercase
$v1 = VlidFactory::v1(lowerCase: true);

// Generate from a specific datetime
$v1 = VlidFactory::v1(new DateTime('2024-01-01 12:00:00.123456'));
```

All methods return a plain `string`.

## Versions

The four VLID versions differ in how many random bits follow the version digit.
A higher version number means more random bits and slightly less deterministic
ordering at the same microsecond:

| Version | Version digit | Random segment length |
|:--------|:--------------|:----------------------|
| V1      | `1`           | 12 chars (60 bits)    |
| V2      | `2`           | varies                |
| V3      | `3`           | varies                |
| V4      | `4`           | varies                |

The version digit is embedded at character position 14 of the identifier (0-indexed),
immediately following the 13-character microsecond timestamp.

## Comparison with ULID

| Property         | ULID                        | VLID                         |
|:-----------------|:----------------------------|:-----------------------------|
| Time precision   | Milliseconds                | Microseconds                 |
| Time encoding    | 10 chars (48 bits)          | 13 chars (~65 bits)          |
| Random bits      | 80 bits (16 chars)          | 60 bits (12 chars) — V1      |
| Versions         | 1                           | 4 (V1–V4)                    |
| Version in ID    | No                          | Yes (position 14)            |
| Total length     | 26 chars                    | 26 chars                     |
| Encoding         | Crockford Base32            | Crockford Base32             |
| Monotonic        | Yes (within millisecond)    | Yes (within microsecond)     |

## Validation

```php
use Valkyrja\Type\Vlid\Factory\VlidFactory;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;

VlidFactory::validate($string);   // validates any VLID version
VlidV1Factory::validate($string); // validates V1 format only
```

Throws `InvalidVlidException` (or `InvalidVlidVnException` for version-specific
factories) on failure. Each factory exposes a `REGEX` constant with its pattern.

## Version Enum

`Valkyrja\Type\Vlid\Enum\Version`:

| Case | Value |
|:-----|:------|
| `V1` | `1`   |
| `V2` | `2`   |
| `V3` | `3`   |
| `V4` | `4`   |