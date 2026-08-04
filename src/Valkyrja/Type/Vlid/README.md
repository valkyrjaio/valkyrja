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
random part encodes 20 bits in 4 characters:

| Version | Random chars | Random bits | Total length |
| :------ | :----------- | :---------- | :----------- |
| V1      | 12           | 60          | 26           |
| V2      | 16           | 80          | 30           |
| V3      | 8            | 40          | 22           |
| V4      | 4            | 20          | 18           |

`Valkyrja\Type\Vlid\Enum\Version` is an int-backed enum with the cases `V1`
through `V4`, whose values are the version digits.

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

All four factory methods share one signature and return a plain `string`:

```php
use Valkyrja\Type\Vlid\Factory\VlidFactory;

$v1 = VlidFactory::v1();                     // current time, uppercase
$v2 = VlidFactory::v2(lowerCase: true);      // lowercase
$v3 = VlidFactory::v3(new DateTime('2024-01-01 12:00:00.123456'));
$v4 = VlidFactory::v4();
```

Like the ULID factory, each VLID factory increments the previous random value
when it generates again at the same microsecond, so the identifiers stay
sorted.

## Validation

```php
use Valkyrja\Type\Vlid\Factory\VlidFactory;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;

VlidFactory::validate($string);   // validates any VLID version
VlidV1Factory::validate($string); // validates the V1 format only
```

On failure, `validate()` throws `InvalidVlidException` from the base factory
or `InvalidVlidVnException` from a version factory. Each factory exposes its
pattern as the `REGEX` constant.
