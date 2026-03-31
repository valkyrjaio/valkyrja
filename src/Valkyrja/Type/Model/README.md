mo# Models

## Introduction

A model is a typed object representation of structured data — a database row, an
API payload, a request body. Valkyrja's model system provides a base class that
handles property access, mass assignment, array serialisation, change tracking,
and optional type casting, all without reflection or magic configuration files.

Models implement `TypeContract`, meaning they integrate naturally with the rest
of the Type component and can be passed anywhere a typed value is expected.

## Defining a Model

Extend `Valkyrja\Type\Model\Abstract\Model` and declare your properties:

```php
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model
{
    public string $name;
    protected string $lastName;
    private string $nickname;
}
```

### How Property Access Works

The model's `__get`, `__set`, and `__isset` magic methods are the heart of the
property access system. PHP only invokes them when a property is inaccessible
from the calling context — which means:

**Public properties** — PHP handles them directly via direct assignment and
access. The magic methods are only invoked during mass-assignment operations
like `fromArray()`, which iterates properties and calls `__set` for each one.

**Protected properties** — from outside the class, `__get` and `__set` are
triggered. The base implementation's fallback — `$this->{$name}` — can reach
protected properties because a parent class method has access to its subclass's
protected members. You can also choose to use the methodology listed for private
properties. Another option is to use property hooks.

**Private properties** — from outside the class, `__get` and `__set` are
triggered, but the base fallback `$this->{$name}` **cannot** reach private
properties defined in a subclass. Private properties **must** be wired up via
the three callable registration methods described below. Another option is to
use property hooks.

### Registering Callables for Private Properties

Override `internalGetCallables()`, `internalSetCallables()`, and
`internalIssetCallables()` to map property names to their access logic:

```php
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model
{
    private string $nickname;
    private string $funname;

    protected function internalGetCallables(): array
    {
        return [
            'nickname' => fn (): string => $this->nickname,
            'funname' => [$this, 'getFunname'],
        ];
    }

    protected function internalSetCallables(): array
    {
        return [
            'nickname' => function (string $value): void {
                $this->nickname = $value;
            },
            'funname' => [$this, 'setFunname'],
        ];
    }

    protected function internalIssetCallables(): array
    {
        return [
            'nickname' => fn (): bool => isset($this->nickname),
            'funname' => [$this, 'issetFunname'],
        ];
    }

    protected function getFunname(): string
    {
        return $this->funname;
    }

    protected function setFunname(string $value): void
    {
        $this->funname = $value;
    }

    protected function issetFunname(): bool
    {
        return isset($this->funname);
    }
}
```

These arrays are checked first on every `__get`, `__set`, and `__isset` call. If
no callable is registered for a property, the base implementation falls through
to `$this->{$name}`.

The callables can also encapsulate validation or transformation logic — they are
not limited to simple property access:

```php
protected function internalSetCallables(): array
{
    return [
        'nickname' => function (string $value): void {
            if (strlen($value) < 2) {
                throw new \InvalidArgumentException('Nickname too short.');
            }
            $this->nickname = strtolower($value);
        },
    ];
}
```

### Constructor Property Promotion

Constructor property promotion works as expected:

```php
class UserModel extends Model
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
```

### Unpacking Properties into the Constructor

By default, `fromArray()` calls `new static()` with no arguments before setting
properties. If your constructor has required parameters, apply the
`UnpackForNewInstance` trait to have the properties array unpacked into the
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

**Via constructor:**

```php
$user = new UserModel(id: '123', name: 'Alice');
```

**From an array** — useful when hydrating from a database row or request body:

```php
$user = UserModel::fromArray(['id' => '123', 'name' => 'Alice']);
```

`fromArray()` calls `__set` for each key-value pair, routing through any
registered callables automatically. Unknown keys (properties that don't exist on
the model) are silently ignored.

**Via `TypeContract::fromValue()`** — accepts an existing instance, an array, or
a JSON string:

```php
$user = UserModel::fromValue($arrayOrJsonString);
```

## Updating Properties

**Mutable update** — modifies the existing instance:

```php
$user->updateProperties(['name' => 'Bob', 'email' => 'bob@example.com']);
```

**Immutable update** — clones the model and applies changes, leaving the
original untouched:

```php
$updated = $user->withProperties(['name' => 'Bob']);
```

## Serialising to Arrays

**`asArray(string ...$properties): array`** — returns public properties as a
key-value array. Pass property names to limit the output:

```php
$array  = $user->asArray();
$subset = $user->asArray('name', 'email');
```

Protected and private properties are excluded by default.
See [Exposing Protected Properties](#exposing-protected-properties) below.

**`asChangedArray(): array`** — returns only the properties that changed after
the model was first populated:

```php
$user->updateProperties(['name' => 'Bob']);
$changed = $user->asChangedArray(); // ['name' => 'Bob']
```

**`asOriginalArray(): array`** — returns the properties as they were when first
set via `__set`:

```php
$original = $user->asOriginalArray();
```

**`getOriginalPropertyValue(string $name): mixed`** — returns the original value
of a single property, or `null` if no original exists.

> Original properties are tracked for any property set through `__set`. This
> means protected and private properties (always via `__set`) and public
> properties during mass assignment. Public properties set via direct assignment
> bypass `__set` and are not tracked.

### Disabling Original Property Tracking

If change tracking is unnecessary, disable it to save memory:

```php
class UserModel extends Model
{
    protected bool $internalShouldSetOriginalProperties = false;
}
```

### JSON Serialisation

Models implement `JsonSerializable`. Passing a model to `json_encode()`
serialises the same properties as `asArray()`. Models also implement
`Stringable`; casting to string returns the JSON-encoded representation.

## Exposing Protected Properties

**Temporary exposure** — expose specific properties for a single call, then
remove them:

```php
$user->expose('lastName');
$array = $user->asArray();
$user->unexpose('lastName');

// Or clear all exposed properties:
$user->unexpose();
```

**Permanent exposure via `ExposableModelContract`** — implement the contract and
apply the `Exposable` trait to declare a static list of always-exposable
properties:

```php
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Trait\Exposable;
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model implements ExposableModelContract
{
    use Exposable;

    protected string $lastName;

    public static function getExposable(): array
    {
        return ['lastName'];
    }
}
```

This adds three additional output methods:

```php
$user->asExposedArray();         // asArray() with exposable properties included
$user->asExposedChangedArray();  // asChangedArray() with exposable properties included
$user->asExposedOnlyArray();     // only the properties from getExposable()
```

### Including All Protected and Private Properties

Apply the `ProtectedExposable` trait to include all protected and private
properties in `asArray()` output. `getExposable()` still controls what
`asExposedOnlyArray()` returns:

```php
use Valkyrja\Type\Model\Trait\ProtectedExposable;
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model
{
    use ProtectedExposable;

    protected string $lastName;
    private string $nickname;

    public static function getExposable(): array
    {
        return ['lastName'];
    }
}
```

## Casting

The default `Model` base does not perform type casting. To cast property values
on assignment, extend `CastableModel` and override `getCastings()`:

```php
use Valkyrja\Type\Model\Abstract\CastableModel;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;

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

When a property is set via mass assignment (e.g. `fromArray()` or
`updateProperties()`), the model calls `TypeContract::fromValue()` on the
declared type and, by default, stores the unwrapped value via `asValue()`.

You can also apply the `Castable` trait directly to a model that cannot extend
`CastableModel`:

```php
use Valkyrja\Type\Model\Trait\Castable;
use Valkyrja\Type\Model\Abstract\Model;

class UserModel extends Model
{
    use Castable;

    public static function getCastings(): array
    {
        return [ /* ... */ ];
    }
}
```

### The Cast Class

`Valkyrja\Type\Data\Cast` accepts any `CastType` enum case or any class that
implements `TypeContract`:

```php
public function __construct(
    CastType|string $type,   // CastType case or TypeContract class name
    bool $convert = true,    // call asValue() after casting (unwrap the type)
    bool $isArray = false    // property holds an array of the type
)
```

**`convert: true`** (default) — after casting, `asValue()` is called, so the
property stores the unwrapped PHP value (e.g. a plain `string` from `StringT`).

**`convert: false`** — the `TypeContract` instance itself is stored on the
property. `OriginalCast` is a shorthand for this:

```php
use Valkyrja\Type\Data\OriginalCast;

'uuid' => new OriginalCast(UuidV4::class),   // property stores a UuidV4 instance
```

**`isArray: true`** — the incoming value is treated as an array and each element
is cast individually. Use `ArrayCast` or `OriginalArrayCast` as shorthands:

```php
use Valkyrja\Type\Data\ArrayCast;
use Valkyrja\Type\Data\OriginalArrayCast;

'tags'   => new ArrayCast(CastType::string),           // array of plain strings
'events' => new OriginalArrayCast(UserEvent::class),   // array of UserEvent instances
```

### CastType Values

`CastType` is a backed enum whose values are the corresponding `TypeContract`
class names:

m| Case | Casts to |
|:------------------------------|:------------------------|
| `CastType::string`            | `string`                |
| `CastType::int`               | `int`                   |
| `CastType::float`             | `float`                 |
| `CastType::bool`              | `bool`                  |
| `CastType::true`              | `true`                  |
| `CastType::false`             | `false`                 |
| `CastType::null`              | `null`                  |
| `CastType::array`             | `array`                 |
| `CastType::object`            | `object` (cast)         |
| `CastType::serialized_object` | unserialized object |
| `CastType::json`              | decoded JSON array |
| `CastType::json_object`       | decoded JSON object |

### Casting to Models, Enums, or Custom Types

Any class implementing `TypeContract` can be used as a cast target by passing
the class name directly:

```php
public static function getCastings(): array
{
    return [
        'address' => new Cast(AddressModel::class),            // nested model, unwrapped
        'uuid'    => new OriginalCast(UuidV4::class),          // kept as UuidV4 instance
        'status'  => new Cast(StatusEnum::class),              // enum via fromValue()
        'tags'    => new ArrayCast(CastType::string),          // array of strings
        'roles'   => new OriginalArrayCast(RoleModel::class),  // array of RoleModel instances
    ];
}
```

## Indexed Models

An indexed model maps property names to integer offsets, enabling compact
positional array representations.

Extend `IndexedModel` (or apply the `Indexable` trait and implement
`IndexedModelContract`):

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

    public static function getReversedIndexes(): array
    {
        return [0 => 'name', 1 => 'email'];
    }
}
```

The indexed methods mirror the named-property equivalents:

```php
$user    = UserModel::fromIndexedArray(['Alice', 'alice@example.com']);
$indexed = $user->asIndexedArray();                          // [0 => 'Alice', 1 => 'alice@...']
$changed = $user->asChangedIndexedArray();
$updated = $user->withIndexedProperties([0 => 'Bob']);
$user->updateIndexedProperties([1 => 'bob@example.com']);

// Conversion utilities
$mapped  = UserModel::getMappedArrayFromIndexedArray($indexedArray);
$indexed = UserModel::getIndexedArrayFromMappedArray($mappedArray);
```
