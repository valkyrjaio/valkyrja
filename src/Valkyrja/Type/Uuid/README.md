# UUID

## Introduction

The UUID subcomponent generates and validates Universally Unique Identifiers.
Versions 1, 3, 4, and 5 follow RFC 4122; the version 6, 7, and 8 formats come
from RFC 9562. Each version is a typed value object that wraps a validated
UUID string and implements `TypeContract<string>` through
`Valkyrja\Type\Uuid\Contract\UuidContract` (`asValue()` and `asFlatValue()`
both return `string`). Each version has a thin contract, `UuidV1Contract`
through `UuidV8Contract`.

## Value Objects

| Class    | Constructor    | Generation when constructed without a string                                                              |
| :------- | :------------- | :-------------------------------------------------------------------------------------------------------- |
| `Uuid`   | `string`       | None — validates a UUID of any version                                                                    |
| `UuidV1` | `string\|null` | Time-based; random 2-byte clock sequence; node from a caller string or random bytes — never a MAC address |
| `UuidV3` | `string`       | None — generate via factory: MD5 of namespace + name                                                      |
| `UuidV4` | `string\|null` | Random: `random_bytes(16)` with version and variant bits                                                  |
| `UuidV5` | `string`       | None — generate via factory: SHA-1 of namespace + name                                                    |
| `UuidV6` | `string\|null` | A V1 UUID with the time fields reordered and version digit `6`                                            |
| `UuidV7` | `string`       | None — generate via factory: a V1 UUID with reordered fields and version digit `7`                        |
| `UuidV8` | `string`       | None — generate via factory: a V1 UUID with reordered fields and version digit `8`                        |

`UuidV1`, `UuidV4`, and `UuidV6` self-generate when constructed with no
argument. The other classes require a string:

```php
use Valkyrja\Type\Uuid\Uuid;
use Valkyrja\Type\Uuid\UuidV4;

$uuid = new Uuid('550e8400-e29b-41d4-a716-446655440000');
$v4   = new UuidV4();  // self-generates a new V4 UUID
```

Each constructor validates a given string and throws the version-specific
exception on failure. `fromValue()` throws `UuidInvalidFromValueException`
when the value is not a string (for the self-generating classes, `null` is
also accepted and triggers generation).

## Generating UUIDs

`UuidFactory` has entry points for versions 1, 3, 4, 5, and 6. It has no
`v7()` or `v8()` method — call those factories directly. All methods return a
plain `string`:

```php
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Factory\UuidV7Factory;
use Valkyrja\Type\Uuid\Factory\UuidV8Factory;

$v1 = UuidFactory::v1();                  // optional node string: v1('my-node')
$v3 = UuidFactory::v3($namespace, $name); // $namespace is itself a valid UUID
$v4 = UuidFactory::v4();
$v5 = UuidFactory::v5($namespace, $name);
$v6 = UuidFactory::v6();                  // optional node string: v6('my-node')
$v7 = UuidV7Factory::generate();          // optional node string
$v8 = UuidV8Factory::generate();          // optional node string
```

## Validation

Each factory exposes a static `validate()` method and a `REGEX` constant. The
base factory validates any version; a version factory validates its own
format only. On failure, `validate()` throws `InvalidUuidException` from the
base factory or `InvalidUuidVnException` from a version factory:

```php
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Factory\UuidV4Factory;

UuidFactory::validate($string);   // any version
UuidV4Factory::validate($string); // V4 format only
```

`Valkyrja\Type\Uuid\Enum\Version` is an int-backed enum with the cases `V1`
and `V3` through `V8`. Each factory exposes its case as the `VERSION`
constant.
