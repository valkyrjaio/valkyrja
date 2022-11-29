# Models

- [Usage](#usage)
- [Defining Models](#defining-models)
    - [Differences Between Protected/Private Properties](#differences-between-protectedprivate-properties)
    - [Naming Convention](#naming-convention)
    - [Protected Getters/Setters](#protected-getterssetters)
    - [Constructor Property Promotion](#constructor-property-promotion)
    - [Defining With a Constructor](#with-a-constructor)
- [Creating Instances](#creating-instances)
    - [Via a Constructor](#via-a-constructor)
    - [Creating From an Array](#creating-from-an-array)
- [Updating Properties](#updating-properties)
    - [Immutable Updating Properties](#immutable-updating-properties)
- [Outputting as an Array](#outputting-as-an-array)
    - [Enhanced Enum Support](#enhanced-enum-support)
    - [Getting Changed Properties](#getting-changed-properties)
    - [With Protected/Private Properties](#with-protectedprivate-properties)
    - [Getting Original Properties](#getting-original-properties)
    - [Getting An Original Property Value](#getting-an-original-property-value)
    - [Don't Set Original Properties](#dont-set-original-properties)
- [Casting](#casting)
    - [Strings, Ints, Floats, Doubles, and Booleans](#to-strings-ints-floats-doubles-and-booleans)
    - [True, False, and Null](#to-true-false-and-null)
    - [JSON](#to-json)
    - [Arrays](#to-arrays)
    - [Objects](#to-objects)
    - [Models](#to-models)
    - [Enums](#to-enums)
- [Indexed Models](#indexed-models)

## Usage

A model can be as simple or complex as you may need it to be. Basic usage involves extending off of the
`Valkyrja\Model\Models\Model` class and adding properties.

## Defining Models

There are multiple ways to define your models.

Most traditionally you can define a model with properties without constructors or getters/setters. Getters and setters
are not required for a Valkyrja Model to work properly. You will only need them if you require additional logic, or
when using `private` properties.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;

class BasicModel extends Model
{
    public string $name;
    protected string $lastName;
    private string $nickname;

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function issetNickname(): bool
    {
        return isset($this->nickname);
    }
}
```

> **Note:** Using getter and setters with public properties is redundant. The magic `__isset`, `__set` and `__get` methods are
> bypassed when using the property directly. However, they are still used internally within the model.

> **Note:** Getters and setters **ARE** required when using private methods.

### Differences Between Protected/Private Properties

By default, there are no differences between `protected` or `private` properties. There are a few use cases that can
make
sense to use `private` over `protected` given that one requires getters and setters. For example, if you would like to
only allow setting a property, but never reading it on its own you can implement the setter, but not the getter. This
would give no way for uses of that property outside the model.

If you would like to treat `public` and `protected` as exposable (with the only difference being that `protected`
properties are forced to go through getters and setters), and private as non-exposable by default you'll need to use
the `Valkyrja\Model\Traits\ExposedProtectedModelTrait` trait.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;
use Valkyrja\Model\Traits\ExposedProtectedModelTrait;

class BasicModel extends Model
{
    use ExposedProtectedModelTrait;

    // ...
}
```

Or extend off of `Valkyrja\Support\Model\Models\ExposedProtectedModel`.

```php
namespace App\Models;

use Valkyrja\Model\Models\ExposedProtectedModel;

class BasicModel extends ExposedProtectedModel
{
    // ...
}
```

If you would like to treat all properties (regardless of visibility) as exposable (with the only difference being that
`protected` and `private` properties are forced to go through getters and setters) you'll need to use the
`Valkyrja\Model\Traits\ExposedModelTrait` trait.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;
use Valkyrja\Model\Traits\ExposedModelTrait;

class BasicModel extends Model
{
    use ExposedModelTrait;

    // ...
}
```

Or extend off of `Valkyrja\Model\Models\ExposedModel`.

```php
namespace App\Models;

use Valkyrja\Model\Models\ExposedModel;

class BasicModel extends ExposedModel
{
    // ...
}
```

### Naming Convention

Getters and setters must adhere to the following naming conventions: start with `get`, `set`, or `isset` followed by
the property name in StudlyCase.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;

class BasicModel extends Model
{
    private string $underscore_property;
    private string $camelCasedProperty;
    private string $UPPERCASE;
    private string $lowercase;

    public function getUnderscoreProperty(): string
    {
        return $this->underscore_property;
    }

    public function getCamelCasedProperty(): string
    {
        return $this->camelCasedProperty;
    }

    public function getUppercase(): string
    {
        return $this->UPPERCASE;
    }

    public function getLowercase(): string
    {
        return $this->lowercase;
    }
}
```

### Protected Getters/Setters

The getters and setters can also be `protected`. They are still used internally, but if you prefer to force usage of
`$model->property` or `$model->property = $value` to get and/or set properties you can set your getters and setters
to `protected` so they are not publicly accessible.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;

class BasicModel extends Model
{
    // ...

    protected function getNickname(): string
    {
        return $this->nickname;
    }

    protected function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }
}
```

> Note: Getters and setters cannot be made `private`.

### Constructor Property Promotion

You can also choose to use Constructor Property Promotion to avoid needing to write additional code to achieve setting
properties during creation.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;

class BasicModelPromotion extends Model
{
    public function __construct(
        public string $name,
        protected string $lastName,
        private string $nickname,
    ) {}

    // ...
}
```

### With a Constructor

If you don't require all the properties, and do not want to set them to nullable, you'll need to go with an
approach that uses the traditional property definitions and a constructor.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;

class BasicModelConstructor extends Model
{
    public string $name;
    protected string $lastName;
    private string $nickname;

    public function __construct(
        string $name = null,
        string $lastName = null,
        string $nickname = null,
    ) {
        if ($name !== null) {
            $this->name = $name;
        }

        if ($lastName !== null) {
            $this->lastName = $lastName;
        }

        if ($nickname !== null) {
            $this->nickname = $nickname;
        }
    }

    // ...
}
```

## Creating Instances

There are a few different ways of creating a new instance of your model. The one you'll be most familiar with is to
create a new instance with the `new` keyword.

```php
use App\Models\BasicModel;

$model = new BasicModel();

$model->name = 'John';
echo $model->name;

$model->lastName = 'Smith';
echo $model->lastName;

$model->nickname = 'J';
echo $model->nickname;
```

### Via a Constructor

If you chose to write your model with constructor property promotion you can simply pass all your values
when creating the instance.

```php
use App\Models\BasicModelPromotion;

$model = new BasicModelPromotion('John', 'Smith', 'J');

echo $model->name;
echo $model->lastName;
echo $model->nickname;
```

Or only set some properties and set others after creation.

```php
use App\Models\BasicModelConstructor;

$model = new BasicModelConstructor(lastName: 'Smith');

$model->name = 'John';
echo $model->name;

echo $model->lastName;

$model->setNickname('J');
echo $model->nickname;
```

### Creating From an Array

There are times when you may have an array of key/value pairs that you need to create a new instance
from. Such use cases can be a server request, or data gathered from a database, etc. The model will always ensure the
property key exists in the model before assigning it via the magic `__set` method.

```php
use App\Models\BasicModel;

$model = BasicModel::fromArray($array);
```

Do note that by default the properties passed to `Model::fromArray` are not passed to the constructor. If you wish to
use array unpacking of the properties to avoid a runtime exception if your constructor has required parameters you will
need to use the `Valkyrja\Model\Traits\UnpackingFromArrayModelTrait` trait.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;
use Valkyrja\Model\Traits\UnpackingFromArrayModelTrait;

class BasicModel extends Model
{
    use UnpackingFromArrayModelTrait;

    //
}
```

Or extend off of `Valkyrja\Model\Models\UnpackingFromArrayModel`.

```php
namespace App\Models;

use Valkyrja\Model\Models\UnpackingFromArrayModel;

class BasicModel extends UnpackingFromArrayModel
{
    // ...
}
```

## Updating Properties

You can always update a model's properties via individual sets, but sometimes you may want to update many properties at
once with a given key/value array.

If you need to update properties for a given model after it has been created you can use the
`updateProperties` method.

Such use cases could involve getting the original data representation of the model then modifying via an incoming
request.

```php
//

$updatedProperties = [
    'name' => 'Joe',
    'lastName' => 'Teller'
];

$model->updateProperties($updatedProperties);
```

### Immutable Updating Properties

If you'd like to update properties but not modify the original model and instead get a new instance with the changes
you can use the `withProperties` method.

```php
//

$newModel = $model->withProperties($updatedProperties);
```

## Outputting as an Array

Whether to set to a JSON response or to save to a database, you'll find yourself needing to get an array representation
of your model.

```php
//

$array = $model->asArray();
```

You can also specify which properties you'd like to limit the output to.

```php
//

$array = $model->asArray('name', ...);
```

```php
//

$listOfProperties = [
    'name',
    // ...
];

$array = $model->asArray(...$listOfProperties);
```

> **Note:** JSON output via the `json_endcode` function is automatically taken care of.

> **Note:** Specifying a property that is protected/private will not automatically output it.<br />
> (See the `Getting Protected/Private Properties` section)

### Enhanced Enum Support

By default `BackedEnum` is the only supported enum type. If you require support for `UnitEnum` you will need to use the
`Valkyrja\Model\Traits\EnhancedEnumModelTrait` trait.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;
use Valkyrja\Model\Traits\EnhancedEnumModelTrait;

class BasicModel extends Model
{
    use EnhancedEnumModelTrait;

    //
}
```

Or extend off of `Valkyrja\Model\Models\EnhancedEnumModel`.

```php
namespace App\Models;

use Valkyrja\Model\Models\EnhancedEnumModel;

class BasicModel extends EnhancedEnumModel
{
    //
}
```

### Getting Changed Properties

If you need to get an array of changed values you can use the `asChangedArray` method.

```php
//

$model->updateProperties($updatedProperties);

$changedProperties = $model->asChangedArray();
```

This method will work for properties updated via the magic `__set`, `updateProperties`, or `withProperties` methods.

```php
//

$model->withProperties($updatedProperties);

$changedProperties = $newModel->asChangedArray();
```

You can also specify which properties you'd like to limit the output to like with the `asArray` method.

```php
//

$changedProperties = $model->asChangedArray('name', ...);
```

```php
//

$listOfProperties = [
    'name',
    // ...
];

$changedProperties = $model->asChangedArray(...$listOfProperties);
```

### With Protected/Private Properties

By default `asArrray` and `asChangedArray` will not output `protected` or `private` properties. This is done
intentionally to provide a way to protecting sensitive data that may not be exposable via an api for example.

To expose protected/private properties you can use the `expose` and `unexpose` methods along with `asArray` or
`asChangedArray`.

```php
//

$this->expose('lastName');

$exposedArray = $model->asArray($toJson);
$exposedChangedArray = $model->asChangedArray($toJson);

$this->unexpose('lastName');
```

You may also use `asExposedArray` if you predefine exposable properties in your model.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;

class BasicModel extends Model
{
    protected static array $exposable = [
        'lastName',
    ];

    // ...
}
```

```php
//

$exposedArray = $model->asExposedArray($toJson);
$exposedChangedArray = $model->asExposedChangedArray($toJson);
```

### Getting Original Properties

If you ever need to get the original property values you used when first creating a model via `Model::fromArray`
you can use the `asOriginalArray` method.

```php
//

$model->updateProperties($updatedProperties);

$originalProperties = $model->asOriginalArray();
```

> **Note:** This will only work for properties that were set via the magic `__set` method. Such as protected and private
> properties or properties set via `Model::fromArray`. Public properties bypass the magic `__set` method.

### Getting An Original Property Value

You can also get an individual property's value via the `getOriginalPropertyValue`. This method will return `null` for a
property that does not have an original value.

```php
//

$originalNameValue = $model->getOriginalPropertyValue('name');
```

### Don't Set Original Properties

If for some reason you don't want to set original properties, for speed or memory saving reasons you can turn this
feature off by setting the `setOriginalPropertiesFromArray` static property to `false`.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;

class BasicModel extends Model
{
    protected static bool $setOriginalPropertiesFromArray = false;

    // ...
}
```

## Casting

Sometimes your data store or where you're setting data from may have a different property type than what your model
expects. To change values on the fly you can take advantage of the model's casting functionality.

The `$casting` static property is an array of key/value pairs where the key is the `property` name and the value is
either a `CastType`, or an array where the first value is a `CastType`.

The default model does not have casting capabilities built in. If you require support for casting you will need to use
the `Valkyrja\Model\Traits\CastableModelTrait` trait.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;
use Valkyrja\Model\Traits\CastableModelTrait;

class BasicModel extends Model
{
    use CastableModelTrait;

    // ...
}
```

Or extend off of `Valkyrja\Model\Models\CastableModel`.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;

class BasicModel extends CastableModel
{
    // ...
}
```

If you choose to use the trait you must not use the `castings` static property, but override the `getCastings` static
method instead.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;
use Valkyrja\Model\Traits\CastableModelTrait;

class BasicModel extends Model
{
    use CastableModelTrait;

    public static function getCastings() : array
    {
        return [
            // ...
        ];
    }

    // ...
}
```

> **Note:** An exception will occur if you try to use the `castings` static property.

### To Strings, Ints, Floats, Doubles, and Booleans

There are simple types and their usage is similarly straight forward and simple. Set the `property` name as the key and
the value to the corresponding `CastType` value you want to cast to.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Enums\CastType;

class BasicModel extends CastableModel
{
    protected static array $castings = [
        'string' => CastType::string,
        'int' => CastType::int,
        'float' => CastType::float,
        'double' => CastType::double,
        'bool' => CastType::bool,
    ];

    public string $string;
    public int $int;
    public float $float;
    public float $double;
    public bool $bool;

    // ...
}
```

### To True, False, and Null

Just like the above types these are simple and straight forward. Their use case however is very limited, however can be
very useful. Examples can include a property that has since introduction had its scope limited, and as such any
incoming value should be overridden.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Enums\CastType;

class BasicModel extends CastableModel
{
    protected static array $castings = [
        'true' => CastType::true,
        'false' => CastType::false,
        'null' => CastType::null,
    ];

    public bool $true;
    public bool $false;
    public null $null;

    // ...
}
```

### To JSON

If the value is a string then it is decoded into an object with `json_decode`. If the value is not a string it falls
back to a simple `(object)` cast.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Enums\CastType;

class BasicModel extends CastableModel
{
    protected static array $castings = [
        'json' => CastType::json,
    ];

    public object $json;

    // ...
}
```

### To Arrays

If the value is a string then it is decoded into an array with `json_decode`. If the value is not a string it falls
back to a simple `(array)` cast.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Enums\CastType;

class BasicModel extends CastableModel
{
    protected static array $castings = [
        'array' => CastType::array,
    ];

    public array $array;

    // ...
}
```

### To Objects

If the value is a string then it is decoded into an object with `unserialize`. If the value is not a string it falls
back to a simple `(object)` cast.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Enums\CastType;

class BasicModel extends CastableModel
{
    protected static array $castings = [
        'object' => CastType::object,
    ];

    public object $object;

    // ...
}
```

By default, no classes are allowed to be unserialized when using the `object` `CastType`. If you require a specific
object to be allowed you'll need to specify the `castingsAllowedClasses` static property.

The `castingsAllowedClasses` static property expects a key/value pair where the key is the `property` name and the
value is an array of string class names.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Enums\CastType;

class BasicModel extends CastableModel
{
    //

    protected static array $castingsAllowedClasses = [
        'object' => [
            Model::class, 
            // ...
        ],
    ];

    // ...
}
```

If you chose to use the `Valkyrja\Model\Traits\CastableModelTrait` trait you must not use the
`castingsAllowedClasses` static property, but override the `getCastingsAllowedClasses` static method instead.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;
use Valkyrja\Model\Traits\CastableModelTrait;

class BasicModel extends Model
{
    use CastableModelTrait;

    //

    public static function getCastingsAllowedClasses() : array
    {
        return [
            // ...
        ];
    }

    // ...
}
```

### To Models

Model casts expect an array for the value in the key/pair in the `$castings` static property. The first value in the
array is the `CastType` and the second value is either a class name, or array with first index of the class name. If
the second value is an array then an array of models will be returned.

The value is always cast to an array if it is not one already before being passed to the model specified as the second
value in the array via the `fromArray` static method.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Enums\CastType;

class BasicModel extends CastableModel
{
    protected static array $castings = [
        'model' => [CastType::model, BasicModel::class],
        'models' => [CastType::model, [BasicModel::class]],
    ];

    public BasicModel $model;
    public array $models;

    // ...
}
```

> **Note:** Multiple models are not supported. The array of model is only to indicate an array of models to be returned.

### To Enums

Enum casts expect an array for the value in the key/pair in the `$castings` static property. The first value in the
array is the `CastType` and the second value is either an enum class name, or array with first index of the enum class
name. If the second value is an array then an array of enums will be returned.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Enums\CastType;

class BasicModel extends CastableModel
{
    protected static array $castings = [
        'enum' => [CastType::model, CastType::class],
        'enums' => [CastType::model, [CastType::class]],
    ];

    public CastType $enum;
    public array $enums;

    // ...
}
```

By default `BackedEnum` is the only supported enum type. If you require support for `UnitEnum` you will need to use the
`Valkyrja\Model\Traits\EnhancedEnumCastableModelTrait` trait.

```php
namespace App\Models;

use Valkyrja\Model\Models\CastableModel;
use Valkyrja\Model\Traits\EnhancedEnumCastableModelTrait;

class BasicModel extends CastableModel
{
    use EnhancedEnumCastableModelTrait;

    // ...
}
```

Or extend off of `Valkyrja\Model\Models\EnhancedEnumCastableModel`.

```php
namespace App\Models;

use Valkyrja\Model\Models\EnhancedEnumCastableModel;

class BasicModel extends EnhancedEnumCastableModel
{
    // ...
}
```

## Indexed Models

There may be times you want to have an indexed representation of your model. Whether to save on bytes between services
or other reasons, you'll need a way to convert an indexed array to your model and vice-versa.

To accomplish this you can use the `Valkyrja\Model\Traits\IndexedModelTrait` and implement the
`Valkyrja\Model\IndexedModel` interface if you wish to. Implementing the contract will allow for additional features
in other modules.

```php
namespace App\Models;

use Valkyrja\Model\Models\Model;
use Valkyrja\Model\IndexedModel;
use Valkyrja\Model\Traits\IndexedModelTrait;

class BasicModel extends Model implements IndexedModel
{
    use IndexedModelTrait;

    // ...
}
```

Or extend off of `Valkyrja\Model\Models\IndexedModel` and the interface will be implemented automatically.

```php
namespace App\Models;

use Valkyrja\Model\Models\IndexedModel;

class BasicModel extends IndexedModel
{
    // ...
}
```
