# VLID

## Introduction

The VLID subcomponent generates and validates Valkyrja Universally Unique
Lexicographically Sortable Identifiers — a Valkyrja-specific extension of the
ULID format with microsecond time precision (`DateTimeInterface` format
`'Uu'`). Every VLID starts with a 13-character Crockford Base32 timestamp,
then a version digit at index 13, then the version's random segment:

```
04YKM75VZG2A8 1 KTFKRFQJ3B69
|-----------| ^ |----------|
  timestamp   |  randomness
  13 chars    |  12 chars (V1)
        version digit
```

## Versions

The four versions differ only in the length of the random segment. Each
random part encodes 20 bits in 4 characters. Pick the version by how many
identifiers one microsecond must hold — more random bits collide less, at the
cost of a longer identifier:

| Version | Random chars | Random bits | Total length |
| :------ | :----------- | :---------- | :----------- |
| V1      | 12           | 60          | 26           |
| V2      | 16           | 80          | 30           |
| V3      | 8            | 40          | 22           |
| V4      | 4            | 20          | 18           |

`Valkyrja\Type\Vlid\Enum\Version` is an int-backed enum with the cases `V1`
through `V4`, whose values are the version digits. Each factory exposes its
case as the `VERSION` constant.

## Comparison with ULID

| Property       | ULID             | VLID                          |
| :------------- | :--------------- | :---------------------------- |
| Time precision | Milliseconds     | Microseconds                  |
| Time encoding  | 10 chars         | 13 chars                      |
| Versions       | 1                | 4, digit embedded at index 13 |
| Total length   | 26 chars         | 18–30 chars by version        |
| Encoding       | Crockford Base32 | Crockford Base32              |

## Value Objects

`Vlid` and `VlidV1` through `VlidV4` implement `TypeContract<string>` through
`VlidContract` (`asValue()` and `asFlatValue()` both return `string`), plus a
thin per-version contract. Every constructor takes `string|null`:

```php
use Valkyrja\Type\Vlid\Vlid;
use Valkyrja\Type\Vlid\VlidV2;

$vlid = new Vlid();                             // self-generates a V1 VLID
$vlid = new Vlid('04YKM75VZG2A81KTFKRFQJ3B69'); // validates any version
$v2   = new VlidV2();                           // self-generates a V2 VLID
```

The constructor validates a given string and throws `InvalidVlidException`
(or `InvalidVlidVnException` from a version class) on failure. `fromValue()`
throws `VlidInvalidFromValueException` when the value is not a string; `null`
triggers generation.

## Generating VLIDs

The four factory methods share one signature —
`(DateTimeInterface|null $dateTime = null, bool $lowerCase = false)` — and
return a plain `string`:

```php
use DateTime;
use Valkyrja\Type\Vlid\Factory\VlidFactory;

$v1 = VlidFactory::v1();                // current time, uppercase
$v2 = VlidFactory::v2(lowerCase: true); // lowercase
$v3 = VlidFactory::v3(new DateTime('2024-01-01 12:00:00.123456'));
$v4 = VlidFactory::v4();
```

The version factories (`VlidV1Factory` through `VlidV4Factory`) expose the
same generation as `generate()` with the same signature.

Like the ULID factory, each VLID factory increments the previous random value
when it generates again at the same microsecond, so the identifiers stay
sorted.

## Validation

Each factory exposes `validate()`, the `bool` `isValid()`, and its pattern as
the `REGEX` constant. The base factory validates any version; a version
factory validates its own format only:

```php
use Valkyrja\Type\Vlid\Factory\VlidFactory;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;

VlidFactory::validate($string);   // any version; throws InvalidVlidException
VlidV1Factory::validate($string); // V1 only; throws InvalidVlidV1Exception
VlidFactory::isValid($string);    // bool, never throws
```

Validation is case-insensitive, so a lowercase VLID validates.

## Using a VLID in a Model Cast

A VLID class is a `TypeContract`, so it works as a model cast target. Use
`OriginalCast` to keep the instance on the property:

```php
use Valkyrja\Type\Data\OriginalCast;
use Valkyrja\Type\Model\Abstract\CastableModel;
use Valkyrja\Type\Vlid\VlidV1;

class EventModel extends CastableModel
{
    public string $name;
    public VlidV1 $id;

    public static function getCastings(): array
    {
        return ['id' => new OriginalCast(VlidV1::class)];
    }
}

$event = EventModel::fromArray([
    'name' => 'user.created',
    'id'   => '04YKM75VZG2A81KTFKRFQJ3B69',
]);

$event->id->asValue(); // the validated VLID string
```

An invalid string fails the cast with `InvalidVlidV1Exception`, so the model
never holds a malformed identifier.
