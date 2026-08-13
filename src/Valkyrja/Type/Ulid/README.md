# ULID

## Introduction

The ULID subcomponent generates and validates Universally Unique
Lexicographically Sortable Identifiers per the
[ULID spec](https://github.com/ulid/spec). A ULID is 26 Crockford Base32
characters: a 10-character millisecond timestamp followed by 16 characters of
randomness. ULIDs are URL-safe and sort lexicographically by generation time,
so they suit primary keys and log or event identifiers that must order by
creation.

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
is not a string; `null` triggers generation. `modify()` returns a new,
validated instance, and `json_encode()` encodes the string.

## Generating ULIDs

Both factory methods return a plain `string`:

```php
use DateTime;
use Valkyrja\Type\Ulid\Factory\UlidFactory;

$ulid = UlidFactory::generate();                            // current time, uppercase
$ulid = UlidFactory::generate(lowerCase: true);             // lowercase
$ulid = UlidFactory::generateLowerCase();                   // lowercase shorthand
$ulid = UlidFactory::generate(new DateTime('2024-01-01'));  // a specific datetime
```

The datetime and case options combine:
`generateLowerCase(new DateTime('2024-01-01'))` and
`generate($dateTime, lowerCase: true)` are equivalent.

## Monotonicity

When the factory generates again within the same millisecond, it increments
the previous random value instead of drawing fresh randomness, so the
identifiers stay sorted. When every random part is at its maximum, the
factory increments the timestamp by one.

## Valid Characters and Validation

Crockford Base32 excludes the ambiguous characters `I`, `L`, `O`, and `U`:

```
0123456789ABCDEFGHJKMNPQRSTVWXYZ
```

The `VALID_CHARACTERS` constant on `UlidFactory` holds the set in both cases.
The `REGEX` constant is `[0-7][valid_chars]{25}` — the leading `[0-7]` keeps
the timestamp within 48 bits. `validate()` throws; `isValid()` returns a
`bool`:

```php
use Valkyrja\Type\Ulid\Factory\UlidFactory;

UlidFactory::validate($string); // throws InvalidUlidException on failure
UlidFactory::isValid($string);  // bool, never throws
```

Validation is case-insensitive, so a lowercase ULID validates.

## Using a ULID in a Model Cast

`Ulid` is a `TypeContract`, so it works as a model cast target. Use
`OriginalCast` to keep the instance on the property:

```php
use Valkyrja\Type\Data\OriginalCast;
use Valkyrja\Type\Model\Abstract\CastableModel;
use Valkyrja\Type\Ulid\Ulid;

class UserModel extends CastableModel
{
    public string $name;
    public Ulid $id;

    public static function getCastings(): array
    {
        return ['id' => new OriginalCast(Ulid::class)];
    }
}

$user = UserModel::fromArray([
    'name' => 'Alice',
    'id'   => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
]);

$user->id->asValue(); // the validated ULID string
```

An invalid string fails the cast with `InvalidUlidException`, so the model
never holds a malformed identifier.
