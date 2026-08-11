# Models

## Introduction

A model is a typed object representation of structured data — a database row,
an API payload, a request body. The base class handles property access, mass
assignment, array serialization, change tracking, and optional type casting.
Models implement `TypeContract`, so a model can go anywhere a typed value is
expected. `ModelContract` also extends `ArrayAccess` and `Stringable`, so
array syntax and string casts work on every model.

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

## How Property Access Works

The `__get`, `__set`, and `__isset` magic methods are the heart of the model.
PHP calls a magic method only when the calling context cannot access the
property, so the three visibilities behave differently:

**Public properties.** PHP reads and writes them directly, and the magic
methods never run for outside access. Mass assignment (`fromArray()`,
`updateProperties()`, `withProperties()`) is the exception: it calls `__set`
for every key, public or not, so set callables, casting, and original-value
recording apply to every mass-assigned property.

**Protected properties.** An outside read or write triggers `__get` or
`__set`. The base fallback `$this->{$name}` reaches the property, because the
base `Model` is a parent class of the model, and a parent's method can access
its subclass's protected members.

**Private properties.** An outside read or write triggers `__get` or `__set`,
but the fallback cannot reach a private property that a subclass declares.
Register callables for each private property, or use property hooks.

`__get` returns `null` for an unset or unknown property. `hasProperty()`
reports whether a property is declared:

```php
$user->hasProperty('name'); // true when the class declares the property
```

### Registering Callables for Private Properties

Override `internalGetCallables()`, `internalSetCallables()`, and
`internalIssetCallables()` to map property names to their access logic. A
callable can be a closure or a method reference:

```php
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model
{
    private string $nickname;
    private string $passphrase;

    protected function internalGetCallables(): array
    {
        return [
            'nickname'   => fn (): string => $this->nickname,
            'passphrase' => [$this, 'getPassphrase'],
        ];
    }

    protected function internalSetCallables(): array
    {
        return [
            'nickname'   => function (string $value): void {
                $this->nickname = $value;
            },
            'passphrase' => [$this, 'setPassphrase'],
        ];
    }

    protected function internalIssetCallables(): array
    {
        return [
            'nickname'   => fn (): bool => isset($this->nickname),
            'passphrase' => [$this, 'issetPassphrase'],
        ];
    }

    protected function getPassphrase(): string
    {
        return $this->passphrase;
    }

    protected function setPassphrase(string $value): void
    {
        $this->passphrase = $value;
    }

    protected function issetPassphrase(): bool
    {
        return isset($this->passphrase);
    }
}
```

Every `__get`, `__set`, and `__isset` call checks these arrays first. When no
callable is registered, the call falls through to `$this->{$name}`.

### Validating and Transforming in a Set Callable

A set callable is not limited to plain assignment. Use one to validate or
transform a value before it is stored. The callable runs for every write that
goes through `__set`, so mass assignment is covered too:

```php
protected function internalSetCallables(): array
{
    return [
        'nickname' => function (string $value): void {
            if (strlen($value) < 2) {
                throw new InvalidArgumentException('Nickname too short.');
            }

            $this->nickname = strtolower($value);
        },
    ];
}
```

## Constructor Property Promotion

Constructor property promotion works as expected:

```php
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model
{
    public function __construct(
        public string $name = '',
        public string $email = '',
    ) {}
}
```

### Unpacking Properties into the Constructor

By default, `fromArray()` calls `new static()` with no arguments before it
sets properties, so a required constructor parameter breaks it. Apply the
`UnpackForNewInstance` trait to unpack the properties array into the
constructor as named arguments:

```php
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Trait\UnpackForNewInstance;

class UserModel extends Model
{
    use UnpackForNewInstance;

    public function __construct(
        public string $id,
        public string $name,
    ) {}
}

$user = UserModel::fromArray(['id' => '123', 'name' => 'Alice']);
```

Two warnings:

- With the trait, every array key must name a constructor parameter. PHP
  throws an `Error` ("Unknown named parameter") for a key that does not.
- Do not mass-assign a `readonly` property. `fromArray()` calls `__set` for
  every key after construction, and the second write to a `readonly` property
  throws an `Error`. Keep `readonly` properties on models that are built only
  through the constructor.

## Creating Instances

Three entry points create a model:

```php
// The constructor
$user = new UserModel(name: 'Alice', email: 'alice@example.com');

// From an array — a database row, a request body
$user = UserModel::fromArray(['name' => 'Alice', 'email' => 'alice@example.com']);

// From a mixed value — an instance, an array, or a JSON string
$user = UserModel::fromValue('{"name":"Alice","email":"alice@example.com"}');
```

`fromArray()` calls `__set` for each key-value pair, so registered callables
and casting apply. `fromValue()` returns a given instance unchanged, decodes
a JSON string, and casts any other value to an array. It throws
`ArrayInvalidStringKeysException` when the resulting array has a non-string
key.

Warning: an unknown key is not ignored. A key that matches no declared
property and no set callable creates a dynamic property on the model, and
that property appears in `asArray()` output. PHP deprecates dynamic property
creation. Filter unknown keys before you call `fromArray()`.

## Updating Properties

`updateProperties()` modifies the existing instance. `withProperties()`
clones the model, applies the changes to the clone, and leaves the original
untouched:

```php
$user->updateProperties(['name' => 'Bob', 'email' => 'bob@example.com']);

$updated = $user->withProperties(['name' => 'Bob']); // $user is unchanged
```

The two differ in original-value recording. `updateProperties()` records
originals when it is the first mass assignment. `withProperties()` never
records the properties it is given, because the clone happens before the set.
See [When Original Values Are Recorded](#when-original-values-are-recorded).

`modify()` — from `TypeContract` — clones the model and passes the clone to
a closure:

```php
$updated = $user->modify(static function (UserModel $user): UserModel {
    $user->name = 'Bob';

    return $user;
});
```

## Array Access and String Casts

Offset reads and writes route through `__get` and `__set`, and a string cast
returns the JSON representation. `isset` and `unset` on an offset behave
differently. The base `Model` runs a plain `isset` / `unset` in its own scope,
so the visibility of the property decides the result:

- `isset` reaches a public or protected property directly, and no
  `internalIssetCallables()` callable runs.
- `isset` cannot reach a private property of a subclass from that scope, so
  PHP calls `__isset`, and the callable does run.
- `Model` declares no `__unset`, so `unset` on a private subclass property
  raises an `Error`.

For the public `name` property:

```php
$user['name'];         // routes through __get
$user['name'] = 'Bob'; // routes through __set
isset($user['name']);  // plain isset in Model's scope — no callable runs
unset($user['name']);  // plain unset — removes the public property

(string) $user;        // {"name":"Bob",...}
```

## Serializing to Arrays

**`asArray(string ...$properties): array`** — the model's public and
protected properties as a key-value array. Private properties of a subclass
are excluded, because the base `Model` collects properties with
`get_object_vars($this)` from its own scope. Each value resolves through
`__get`, so get callables apply. Pass property names to limit the output:

```php
$array  = $user->asArray();
$subset = $user->asArray('name', 'email');
```

**`asChangedArray(): array`** — the properties whose current value differs
from the recorded original value, by strict comparison:

```php
$user = UserModel::fromArray(['name' => 'Alice', 'email' => 'a@example.com']);
$user->updateProperties(['name' => 'Bob']);

$user->asChangedArray(); // ['name' => 'Bob']
```

**`asOriginalArray(): array`** — all recorded original values.

**`getOriginalPropertyValue(string $name): mixed`** — one original value, or
`null` when none was recorded.

### When Original Values Are Recorded

`__set` records the first value it receives for each property, and it stops
recording after the first mass assignment completes. The rules:

- `fromArray()` and `updateProperties()` each count as a mass assignment.
  The properties that the first call sets are recorded; later calls record
  nothing.
- Any `__set` before the first mass assignment records too — for example, a
  write to a protected property from outside the class.
- A recorded `null` does not hold. The guard checks the record with
  `isset()`, which is `false` for `null`, so the next `__set` before the
  first mass assignment replaces a recorded `null` original with its value —
  and `asChangedArray()` then misses the change.
- `__clone` also stops the recording. `withProperties()` clones before it
  sets, so it never records the properties it is given — not even on a model
  with no prior mass assignment. The clone keeps the originals recorded so
  far.
- A direct assignment to a public property bypasses `__set` and is never
  recorded.

To disable the recording, set
`protected bool $internalShouldSetOriginalProperties = false;` on the model.

### JSON Serialization

Models implement `JsonSerializable`, and `json_encode()` serializes the same
properties as `asArray()`. `asFlatValue()` and the string cast return the
JSON-encoded string. `asValue()` returns the model itself.

## Exposing Properties

The base `Model` has no `expose()` or `unexpose()` method. Those methods come
from the `Exposable` trait, which supplies the methods that
`ExposableModelContract` declares. The class that uses the trait must still
declare `implements ExposableModelContract`. Use the trait when serialization
must include a name that the property collection misses — for example, a
private property with a registered get callable:

```php
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Trait\Exposable;

class UserModel extends Model implements ExposableModelContract
{
    use Exposable;

    public string $name;
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

    public static function getExposable(): array
    {
        return ['nickname'];
    }
}
```

The trait adds three output methods. Each one exposes the `getExposable()`
names for the duration of the call, then unexposes them:

```php
$user = UserModel::fromArray(['name' => 'Alice', 'nickname' => 'Al']);

$user->asArray();               // ['name' => 'Alice']
$user->asExposedArray();        // ['name' => 'Alice', 'nickname' => 'Al']
$user->asExposedChangedArray(); // [] — nothing differs from the originals
$user->asExposedOnlyArray();    // ['nickname' => 'Al']
```

`asExposedChangedArray()` is `asChangedArray()` computed with the exposable
properties exposed, so an exposable property appears only when its current
value differs from its recorded original. Here `nickname` still equals the
`'Al'` that `fromArray()` recorded, so the result is empty.

`asExposedArray()` also accepts property names to limit the output, like
`asArray()`.

`expose()` and `unexpose()` control the exposure manually. An exposed name
stays in every serialization — `asArray()` and `json_encode()` both — until
it is unexposed:

```php
$user->expose('nickname');
$user->asArray();   // ['name' => 'Alice', 'nickname' => 'Al']
json_encode($user); // {"name":"Alice","nickname":"Al"}

$user->unexpose('nickname'); // unexpose one name
$user->unexpose();           // or clear all exposed names
```

On a plain `Model`, `asArray()` already contains the protected properties, so
exposing adds nothing for them.

### Hiding Protected Properties Until Exposed

The `ProtectedExposable` trait uses `Exposable` and changes the property
source: `asArray()` returns only the public properties plus the currently
exposed names. Protected and private properties stay out of every output —
`json_encode()` included — until exposed. Use it for a model whose default
serialization crosses a trust boundary:

```php
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Trait\ProtectedExposable;

class UserModel extends Model implements ExposableModelContract
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

### Choosing an Exposure Setup

- `Exposable` — the default output is right, and a few extra names must join
  it on demand.
- `ProtectedExposable` — the default output must hide everything that is not
  public.
- `ExposableModelContract` — type-hint this contract where code needs the
  exposed output methods.

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

$user = UserModel::fromArray(['name' => 'Alice', 'age' => '30']);
$user->age; // 30
```

A model that cannot extend `CastableModel` can apply the `Castable` trait
directly and implement `CastableModelContract`. The model reads
`getCastings()` once per class and caches the result for the process, so the
method must return the same array on every call.

### How a Cast Applies

For each property with a cast, mass assignment:

1. Skips the cast when the value is `null`.
2. Skips `fromValue()` when the value already is an instance of the declared
   type.
3. Otherwise calls `TypeContract::fromValue()` on the declared type.
4. Stores `asValue()` of the result by default (`convert: true`), or the
   type instance itself (`convert: false`).

Casting applies only during mass assignment — `fromArray()`, `fromValue()`,
`updateProperties()`, and `withProperties()`. A direct property write is
never cast. To adjust every cast result before it is stored, override the
`internalModifyCastPropertyValue(TypeContract $type): TypeContract` hook.

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

| Shorthand           | Equivalent                                       |
| :------------------ | :----------------------------------------------- |
| `OriginalCast`      | `new Cast($type, convert: false)`                |
| `ArrayCast`         | `new Cast($type, isArray: true)`                 |
| `OriginalArrayCast` | `new Cast($type, convert: false, isArray: true)` |

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

### Casting to Models, Enums, and Custom Types

Any class that implements `TypeContract` is a valid cast target — another
model, an enum that uses the `Enumerable` trait, an identifier type, or a
custom type class:

```php
use Valkyrja\Type\Data\ArrayCast;
use Valkyrja\Type\Data\OriginalArrayCast;
use Valkyrja\Type\Data\OriginalCast;

public static function getCastings(): array
{
    return [
        'address' => new Cast(AddressModel::class),           // nested model, unwrapped
        'uuid'    => new OriginalCast(UuidV4::class),         // kept as a UuidV4 instance
        'status'  => new Cast(StatusEnum::class),             // enum via fromValue()
        'tags'    => new ArrayCast(CastType::string),         // array of plain strings
        'roles'   => new OriginalArrayCast(RoleModel::class), // array of RoleModel instances
    ];
}
```

### Entity Casting in the ORM

The ORM extends `Cast` with `Valkyrja\Orm\Data\EntityCast`, which adds a
`column` name and a `relationships` list for entity properties. See the ORM
component for usage.

## Indexed Models

An indexed model maps property names to integer offsets, so the model can
serialize to a compact positional array — a cache entry or a wire format
where repeated string keys would waste space. Extend `IndexedModel`, or apply
the `Indexable` trait and implement `IndexedModelContract`. Override only
`getIndexes()` — `getReversedIndexes()` flips and caches it automatically:

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

Two conversion rules: an index or name that `getIndexes()` does not map is
dropped, and the output is sorted by index.

### Exposing Indexed Output

The `ExposableIndexable` trait combines `Exposable` and `Indexable` for
models that implement `ExposableIndexedModelContract`. It adds:

```php
$user->asExposedIndexedArray();        // asExposedArray() as a positional array
$user->asExposedChangedIndexedArray(); // asExposedChangedArray() as a positional array
```
