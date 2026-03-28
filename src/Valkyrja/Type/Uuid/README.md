# UUID

## Introduction

The UUID subcomponent generates and validates Universally Unique Identifiers
(UUIDs) conforming to RFC 4122. All versions (V1, V3, V4, V5, V6, V7, V8) are
supported. Each version is a typed value object wrapping a validated UUID string
and integrating with the Type component's `TypeContract`.

## The UuidContract

`Valkyrja\Type\Uuid\Contract\UuidContract` extends `TypeContract<string>`:

```php
public function asValue(): string;
public function asFlatValue(): string;
```

Each version has its own contract (`UuidV1Contract` through `UuidV8Contract`)
extending `UuidContract`. These are thin — the version distinction is expressed
through the value object class rather than additional methods.

## Value Objects

Instantiate a UUID value object by passing a validated UUID string. The
constructor calls `UuidFactory::validate()` and throws `InvalidUuidException` on
failure:

```php
use Valkyrja\Type\Uuid\Uuid;
use Valkyrja\Type\Uuid\UuidV4;

$uuid = new Uuid('550e8400-e29b-41d4-a716-446655440000');
$v4   = new UuidV4('550e8400-e29b-41d4-a716-446655440000');

// Or from an unknown value (throws InvalidArgumentException for non-strings):
$uuid = Uuid::fromValue($someValue);
```

| Class    | Contract          | Description                       |
|:---------|:------------------|:----------------------------------|
| `Uuid`   | `UuidContract`    | Generic UUID (validates any version) |
| `UuidV1` | `UuidV1Contract`  | Version 1 (time-based)            |
| `UuidV3` | `UuidV3Contract`  | Version 3 (name-based, MD5)       |
| `UuidV4` | `UuidV4Contract`  | Version 4 (random)                |
| `UuidV5` | `UuidV5Contract`  | Version 5 (name-based, SHA-1)     |
| `UuidV6` | `UuidV6Contract`  | Version 6 (reordered time-based)  |
| `UuidV7` | `UuidV7Contract`  | Version 7 (sortable time-based)   |
| `UuidV8` | `UuidV8Contract`  | Version 8 (custom/experimental)   |

## Generating UUIDs

All generation is done through static factory methods. Use `UuidFactory` as a
single entry point or call the version-specific factory directly:

```php
use Valkyrja\Type\Uuid\Factory\UuidFactory;

// Version 1 — time-based, optional node identifier
$v1 = UuidFactory::v1();                     // random node
$v1 = UuidFactory::v1('my-server-node');     // named node

// Version 3 — name-based (MD5 hash of namespace + name)
$v3 = UuidFactory::v3($namespace, $name);

// Version 4 — random
$v4 = UuidFactory::v4();

// Version 5 — name-based (SHA-1 hash of namespace + name)
$v5 = UuidFactory::v5($namespace, $name);

// Version 6 — reordered time-based (monotonically sortable)
$v6 = UuidFactory::v6();
$v6 = UuidFactory::v6('my-server-node');

// Version 7 — time-sortable (derived from V1 with reordered fields)
// (use UuidV7Factory::generate() directly)
```

All methods return a plain `string` in standard UUID format:
`xxxxxxxx-xxxx-Mxxx-Nxxx-xxxxxxxxxxxx`

## Validation

Each factory exposes a `validate()` static method. It throws the
version-specific `InvalidUuidVnException` (or `InvalidUuidException` for the
base factory) if the string does not match the expected format:

```php
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Factory\UuidV4Factory;

UuidFactory::validate($string);   // validates any version
UuidV4Factory::validate($string); // validates V4 format only
```

Each factory also exposes a `REGEX` constant with the version-specific pattern.

## Version Enum

`Valkyrja\Type\Uuid\Enum\Version` lists all supported versions:

| Case | Value |
|:-----|:------|
| `V1` | `1`   |
| `V3` | `3`   |
| `V4` | `4`   |
| `V5` | `5`   |
| `V6` | `6`   |
| `V7` | `7`   |
| `V8` | `8`   |

## Generation Details

| Version | Algorithm                                                          |
|:--------|:-------------------------------------------------------------------|
| V1      | Microsecond timestamp + sequence counter + node (MAC or random)    |
| V3      | MD5 hash of a namespace UUID + a name string                       |
| V4      | 16 random bytes via `random_bytes(16)` with version/variant bits   |
| V5      | SHA-1 hash of a namespace UUID + a name string                     |
| V6      | V1 timestamp fields reordered for lexicographic sortability        |
| V7      | V1 fields rearranged to place time-high first (sortable)           |
| V8      | Custom layout — reserved for experimental or application use       |