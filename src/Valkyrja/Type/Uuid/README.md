# UUID

## Introduction

The UUID subcomponent generates and validates Universally Unique Identifiers.
Versions 1, 3, 4, and 5 follow RFC 4122, and version 6 follows RFC 9562.
The version 7 factory emits a reordered version 1 layout, not the RFC 9562
Unix-millisecond layout, and version 8 is the free-form version (see the
generation table). Each version is a typed value object that wraps a
validated UUID string and implements `TypeContract<string>` through
`Valkyrja\Type\Uuid\Contract\UuidContract` (`asValue()` and `asFlatValue()`
both return `string`). Each version has a thin contract. The contracts are
`UuidV1Contract`, and `UuidV3Contract` through `UuidV8Contract`.

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
exception on failure. `Uuid` throws `InvalidUuidException`. The version
classes throw `InvalidUuidV1Exception`, and `InvalidUuidV3Exception` through
`InvalidUuidV8Exception`. `fromValue()` throws
`UuidInvalidFromValueException` when the value is not a string; the
self-generating classes also accept `null`, which triggers generation.

## Generating UUIDs

`UuidFactory` has entry points for versions 1, 3, 4, 5, and 6. It has no
`v7()` or `v8()` method. Call those factories directly. All methods return a
plain `string` in the standard `xxxxxxxx-xxxx-Mxxx-Nxxx-xxxxxxxxxxxx` form:

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

Generation notes:

- **V1** encodes the current time, a random 2-byte clock sequence, and a
  node. A given node string is hashed when it is not hexadecimal, and a
  random 16-byte node is drawn when none is given. The factory never reads a
  MAC address. V6, V7, and V8 build on the V1 algorithm and accept the same
  optional node.
- **V3 and V5** are deterministic: the same namespace and name always
  produce the same UUID. The namespace must be a valid UUID of any version;
  an invalid namespace throws `InvalidUuidException`.
- **V4** is 16 random bytes with the version and variant bits set.

Name-based generation fits stable derived identifiers:

```php
use Valkyrja\Type\Uuid\Factory\UuidFactory;

$namespace = '550e8400-e29b-41d4-a716-446655440000'; // one per application
$userUuid  = UuidFactory::v5($namespace, 'user:42'); // always the same result
```

## Validation

Each factory exposes a static `validate()` method, a `bool` `isValid()`
method, and a `REGEX` constant. The base factory validates any version; a
version factory validates its own format only. On failure, `validate()`
throws `InvalidUuidException` from the base factory or
`InvalidUuidVnException` from a version factory:

```php
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Factory\UuidV4Factory;

UuidFactory::validate($string);    // any version; throws on failure
UuidV4Factory::validate($string);  // V4 format only; throws on failure
UuidV4Factory::isValid($string);   // bool, never throws
```

`Valkyrja\Type\Uuid\Enum\Version` is an int-backed enum with the cases `V1`
and `V3` through `V8`. Each factory exposes its case as the `VERSION`
constant.

## Using a UUID in a Model Cast

A UUID class is a `TypeContract`, so it works as a model cast target. Use
`OriginalCast` to keep the instance on the property:

```php
use Valkyrja\Type\Data\OriginalCast;
use Valkyrja\Type\Model\Abstract\CastableModel;
use Valkyrja\Type\Uuid\UuidV4;

class UserModel extends CastableModel
{
    public string $name;
    public UuidV4 $uuid;

    public static function getCastings(): array
    {
        return ['uuid' => new OriginalCast(UuidV4::class)];
    }
}

$user = UserModel::fromArray([
    'name' => 'Alice',
    'uuid' => '550e8400-e29b-41d4-a716-446655440000',
]);

$user->uuid->asValue(); // the validated UUID string
```

An invalid UUID string fails the cast with `InvalidUuidV4Exception`, so the
model never holds a malformed identifier.
