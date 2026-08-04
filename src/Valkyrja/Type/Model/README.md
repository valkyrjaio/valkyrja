# Models

## Introduction

A model is a typed object representation of structured data — a database row,
an API payload, a request body. The base class handles property access, mass
assignment, array serialization, change tracking, and optional type casting.
Models implement `TypeContract`, so a model can go anywhere a typed value is
expected.

## Defining a Model

Extend `Valkyrja\Type\Model\Abstract\Model` and declare the properties:

```php
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model
{
    public string $name;
    protected string $lastName;
    private string $nickname;
}
```

Constructor property promotion works as expected.

## Property Access

PHP calls `__get` and `__set` only for a property that the calling context
cannot access. Three rules follow from that:

1. PHP assigns and reads public properties directly. Mass assignment
   (`fromArray()`, `updateProperties()`, `withProperties()`) calls `__set` for
   every key, public or not.
2. The magic-method fallback `$this->{$name}` reaches protected properties,
   because the base `Model` is a parent class of the model.
3. The fallback cannot reach a private property of a subclass. Register
   callables for private properties, or use property hooks.

### Registering Callables for Private Properties

Override `internalGetCallables()`, `internalSetCallables()`, and
`internalIssetCallables()` to map property names to their access logic:

```php
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model
{
    private string $nickname;

    protected function internalGetCallables(): array
    {
        return ['nickname' => fn (): string => $this->nickname];
    }

    protected function internalSetCallables(): array
    {
        return [
            'nickname' => function (string $value): void {
                $this->nickname = $value;
            },
        ];
    }

    protected function internalIssetCallables(): array
    {
        return ['nickname' => fn (): bool => isset($this->nickname)];
    }
}
```

Every `__get`, `__set`, and `__isset` call checks these arrays first. When no
callable is registered, the call falls through to `$this->{$name}`. A callable
can also validate or transform the value before it stores the value.

### Unpacking Properties into the Constructor

By default, `fromArray()` calls `new static()` with no arguments before it
sets properties. When the constructor has required parameters, apply the
`UnpackForNewInstance` trait. The trait unpacks the properties array into the
constructor:

```php
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Trait\UnpackForNewInstance;

class UserModel extends Model
{
    use UnpackForNewInstance;

    public function __construct(
        public readonly string $id,
        public string $name,
    ) {}
}
```

## Creating Instances

`fromArray()` calls `__set` for each key-value pair, so registered callables
apply. `fromValue()` accepts an existing instance, an array, or a JSON string:

```php
$user = UserModel::fromArray(['id' => '123', 'name' => 'Alice']);
$user = UserModel::fromValue($arrayOrJsonString);
```

Warning: an unknown key is not ignored. A key that matches no declared
property and no set callable creates a dynamic property on the model, and
that property appears in `asArray()` output. PHP deprecates dynamic property
creation. Filter unknown keys before you call `fromArray()`.

## Updating Properties

`updateProperties()` modifies the existing instance. `withProperties()`
clones the model, applies the changes, and leaves the original untouched:

```php
$user->updateProperties(['name' => 'Bob', 'email' => 'bob@example.com']);
$updated = $user->withProperties(['name' => 'Bob']);
```

## Serializing to Arrays

**`asArray(string ...$properties): array`** — returns the model's public and
protected properties as a key-value array. Private properties of a subclass
are excluded, because the base `Model` collects properties with
`get_object_vars($this)` from its own scope. Each value resolves through
`__get`, so get callables apply. Pass property names to limit the output:

```php
$array  = $user->asArray();
$subset = $user->asArray('name', 'email');
```

**`asChangedArray(): array`** — returns the properties whose current value
differs from the recorded original value.

**`asOriginalArray(): array`** — returns the recorded original values.

**`getOriginalPropertyValue(string $name): mixed`** — returns one original
value, or `null` when none was recorded.

### When Original Values Are Recorded

`__set` records the first value it receives for each property, and it stops
recording after the first mass assignment completes. `fromArray()`,
`updateProperties()`, and `withProperties()` each count as a mass assignment.
`__clone` also stops the recording, so a clone records no new originals. A
direct assignment to a public property bypasses `__set` and is never recorded.

To disable the recording, set
`protected bool $internalShouldSetOriginalProperties = false;` on the model.

### JSON Serialization

Models implement `JsonSerializable`, and `json_encode()` serializes the same
properties as `asArray()`. Models also implement `Stringable`; the string cast
returns the JSON-encoded representation.

## Exposing Properties

The base `Model` has no `expose()` or `unexpose()` method. Those methods come
from the `Exposable` trait, which implements `ExposableModelContract`:

```php
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Trait\Exposable;

class UserModel extends Model implements ExposableModelContract
{
    use Exposable;

    public static function getExposable(): array
    {
        return ['nickname'];
    }
}
```

The trait adds three output methods. Each one exposes the `getExposable()`
names for the duration of the call, then unexposes them:

```php
$user->asExposedArray();        // asArray() plus the exposable properties
$user->asExposedChangedArray(); // asChangedArray() plus the exposable properties
$user->asExposedOnlyArray();    // only the properties from getExposable()

$user->expose('nickname');      // expose manually until unexposed
$user->unexpose('nickname');    // or unexpose() to clear all
```

On a plain `Model`, `asArray()` already contains the protected properties, so
exposing adds nothing for them. Exposing adds a name that the property
collection missed — for example, a private property with a registered get
callable.

### Hiding Protected Properties Until Exposed

The `ProtectedExposable` trait uses `Exposable` and changes the property
source: `asArray()` returns only the public properties plus the currently
exposed names. Protected and private properties stay out of the output until
exposed:

```php
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Trait\ProtectedExposable;

class UserModel extends Model
{
    use ProtectedExposable;

    public string $name;
    protected string $lastName;

    public static function getExposable(): array
    {
        return ['lastName'];
    }
}

$user->asArray();        // ['name' => ...]
$user->asExposedArray(); // ['name' => ..., 'lastName' => ...]
```

## Casting

The base `Model` performs no type casting. To cast property values on mass
assignment, extend `CastableModel` and override the static `getCastings()`:

```php
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;
use Valkyrja\Type\Model\Abstract\CastableModel;

class UserModel extends CastableModel
{
    public string $name;
    public int $age;

    public static function getCastings(): array
    {
        return [
            'name' => new Cast(CastType::string),
            'age'  => new Cast(CastType::int),
        ];
    }
}
```

Casting applies only during mass assignment. For each cast property, the model
calls `TypeContract::fromValue()` on the declared type and, by default, stores
the unwrapped value from `asValue()`. A `null` value skips the cast. A value
that already is an instance of the declared type skips `fromValue()`.

A model that cannot extend `CastableModel` can apply the `Castable` trait
directly and implement `CastableModelContract`.

### The Cast Class

`Valkyrja\Type\Data\Cast` accepts a `CastType` case or the name of any class
that implements `TypeContract`:

```php
public function __construct(
    CastType|string $type,   // CastType case or TypeContract class name
    bool $convert = true,    // store asValue() instead of the type instance
    bool $isArray = false    // cast each element of an incoming array
)
```

`convert: false` stores the `TypeContract` instance itself; `OriginalCast` is
the shorthand. `isArray: true` casts each element of the incoming array;
`ArrayCast` and `OriginalArrayCast` are the shorthands:

```php
use Valkyrja\Type\Data\ArrayCast;
use Valkyrja\Type\Data\OriginalArrayCast;
use Valkyrja\Type\Data\OriginalCast;

public static function getCastings(): array
{
    return [
        'address' => new Cast(AddressModel::class),            // nested model, unwrapped
        'uuid'    => new OriginalCast(UuidV4::class),          // kept as a UuidV4 instance
        'status'  => new Cast(StatusEnum::class),              // enum via fromValue()
        'tags'    => new ArrayCast(CastType::string),          // array of plain strings
        'roles'   => new OriginalArrayCast(RoleModel::class),  // array of RoleModel instances
    ];
}
```

### CastType Values

`CastType` is a string-backed enum whose values are `TypeContract` class
names. With the default `convert: true`, the property stores the `asValue()`
result of that type class:

| Case                          | Type class         |
| :---------------------------- | :----------------- |
| `CastType::string`            | `StringT`          |
| `CastType::int`               | `IntT`             |
| `CastType::float`             | `FloatT`           |
| `CastType::bool`              | `BoolT`            |
| `CastType::true`              | `TrueT`            |
| `CastType::false`             | `FalseT`           |
| `CastType::null`              | `NullT`            |
| `CastType::array`             | `ArrayT`           |
| `CastType::object`            | `ObjectT`          |
| `CastType::serialized_object` | `SerializedObject` |
| `CastType::json`              | `Json`             |
| `CastType::json_object`       | `JsonObject`       |

## Indexed Models

An indexed model maps property names to integer offsets for compact positional
arrays. Extend `IndexedModel`, or apply the `Indexable` trait and implement
`IndexedModelContract`. Override only `getIndexes()` — `getReversedIndexes()`
flips it automatically:

```php
use Valkyrja\Type\Model\Abstract\IndexedModel;

class UserModel extends IndexedModel
{
    public string $name;
    public string $email;

    public static function getIndexes(): array
    {
        return ['name' => 0, 'email' => 1];
    }
}
```

The indexed methods mirror the named-property equivalents:

```php
$user    = UserModel::fromIndexedArray(['Alice', 'alice@example.com']);
$indexed = $user->asIndexedArray();          // [0 => 'Alice', 1 => 'alice@...']
$changed = $user->asChangedIndexedArray();
$orig    = $user->asOriginalIndexedArray();
$updated = $user->withIndexedProperties([0 => 'Bob']);
$user->updateIndexedProperties([1 => 'bob@example.com']);

// Conversion utilities
$mapped  = UserModel::getMappedArrayFromIndexedArray($indexedArray);
$indexed = UserModel::getIndexedArrayFromMappedArray($mappedArray);
```
