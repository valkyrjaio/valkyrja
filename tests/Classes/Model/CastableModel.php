<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Classes\Model;

use Valkyrja\Tests\Classes\Enum\Enum;
use Valkyrja\Tests\Classes\Enum\IntEnum;
use Valkyrja\Tests\Classes\Enum\StringEnum;
use Valkyrja\Type\Model\CastableModel as AbstractModel;
use Valkyrja\Type\Model\Data\ArrayCast;
use Valkyrja\Type\Model\Data\Cast;
use Valkyrja\Type\Model\Data\OriginalArrayCast;
use Valkyrja\Type\Model\Data\OriginalCast;
use Valkyrja\Type\Model\Enum\CastType;

/**
 * Model class to use to test Castable model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class CastableModel extends AbstractModel
{
    public const ARRAY_PROPERTY                   = 'array';
    public const ARRAY_ARRAY_PROPERTY             = 'arrayArray';
    public const BOOL_PROPERTY                    = 'bool';
    public const BOOL_ARRAY_PROPERTY              = 'boolArray';
    public const DOUBLE_PROPERTY                  = 'double';
    public const DOUBLE_ARRAY_PROPERTY            = 'doubleArray';
    public const FLOAT_PROPERTY                   = 'float';
    public const FLOAT_ARRAY_PROPERTY             = 'floatArray';
    public const INT_PROPERTY                     = 'int';
    public const INT_ARRAY_PROPERTY               = 'intArray';
    public const STRING_PROPERTY                  = 'string';
    public const STRING_ARRAY_PROPERTY            = 'stringArray';
    public const OBJECT_PROPERTY                  = 'object';
    public const OBJECT_ARRAY_PROPERTY            = 'objectArray';
    public const SERIALIZED_OBJECT_PROPERTY       = 'serializedObject';
    public const SERIALIZED_OBJECT_ARRAY_PROPERTY = 'serializedObjectArray';
    public const JSON_PROPERTY                    = 'json';
    public const JSON_ARRAY_PROPERTY              = 'jsonArray';
    public const JSON_OBJECT_PROPERTY             = 'jsonObject';
    public const JSON_OBJECT_ARRAY_PROPERTY       = 'jsonObjectArray';
    public const TRUE_PROPERTY                    = 'trueVal';
    public const TRUE_ARRAY_PROPERTY              = 'trueArray';
    public const FALSE_PROPERTY                   = 'falseVal';
    public const FALSE_ARRAY_PROPERTY             = 'falseArray';
    public const NULL_PROPERTY                    = 'nullVal';
    public const NULL_ARRAY_PROPERTY              = 'nullArray';
    public const MODEL_PROPERTY                   = 'model';
    public const MODEL_ARRAY_PROPERTY             = 'modelArray';
    public const ENUM_PROPERTY                    = 'enum';
    public const ENUM_ARRAY_PROPERTY              = 'enumArray';
    public const STRING_ENUM_PROPERTY             = 'stringEnum';
    public const STRING_ENUM_ARRAY_PROPERTY       = 'stringEnumArray';
    public const INT_ENUM_PROPERTY                = 'intEnum';
    public const INT_ENUM_ARRAY_PROPERTY          = 'intEnumArray';
    public const ID_PROPERTY                      = 'id';
    public const ID_ARRAY_PROPERTY                = 'idArray';
    public const INT_ID_PROPERTY                  = 'intId';
    public const INT_ID_ARRAY_PROPERTY            = 'intIdArray';
    public const STRING_ID_PROPERTY               = 'stringId';
    public const STRING_ID_ARRAY_PROPERTY         = 'stringIdArray';

    public array $array;
    /** @var array[] */
    public array $arrayArray;

    public bool $bool;
    /** @var bool[] */
    public array $boolArray;

    public float $double;
    /** @var float[] */
    public array $doubleArray;

    public float $float;
    /** @var float[] */
    public array $floatArray;

    public int $int;
    /** @var int[] */
    public array $intArray;

    public string $string;
    /** @var string[] */
    public array $stringArray;

    public object $object;
    /** @var object[] */
    public array $objectArray;

    public object $serializedObject;
    /** @var object[] */
    public array $serializedObjectArray;

    public array $json;
    /** @var array[] */
    public array $jsonArray;

    public object $jsonObject;
    /** @var object[] */
    public array $jsonObjectArray;

    public bool $trueVal;
    /** @var true[] */
    public array $trueArray;

    public bool $falseVal;
    /** @var false[] */
    public array $falseArray;

    /** @var null */
    public mixed $nullVal;
    /** @var null[] */
    public array $nullArray;

    public Model $model;
    /** @var Model[] */
    public array $modelArray;

    public Enum $enum;
    /** @var Enum[] */
    public array $enumArray;

    public StringEnum $stringEnum;
    /** @var StringEnum[] */
    public array $stringEnumArray;

    public IntEnum $intEnum;
    /** @var IntEnum[] */
    public array $intEnumArray;

    public string|int $id;
    /** @var string[]|int[] */
    public array $idArray;

    /**
     * @inheritDoc
     */
    public static function getCastings(): array
    {
        return [
            self::ARRAY_PROPERTY                   => new Cast(CastType::array),
            self::ARRAY_ARRAY_PROPERTY             => new ArrayCast(CastType::array),
            self::BOOL_PROPERTY                    => new Cast(CastType::bool),
            self::BOOL_ARRAY_PROPERTY              => new ArrayCast(CastType::bool),
            self::DOUBLE_PROPERTY                  => new Cast(CastType::double),
            self::DOUBLE_ARRAY_PROPERTY            => new ArrayCast(CastType::double),
            self::FLOAT_PROPERTY                   => new Cast(CastType::float),
            self::FLOAT_ARRAY_PROPERTY             => new ArrayCast(CastType::float),
            self::INT_PROPERTY                     => new Cast(CastType::int),
            self::INT_ARRAY_PROPERTY               => new ArrayCast(CastType::int),
            self::STRING_PROPERTY                  => new Cast(CastType::string),
            self::STRING_ARRAY_PROPERTY            => new ArrayCast(CastType::string),
            self::OBJECT_PROPERTY                  => new Cast(CastType::object),
            self::OBJECT_ARRAY_PROPERTY            => new ArrayCast(CastType::object),
            self::SERIALIZED_OBJECT_PROPERTY       => new Cast(CastType::serialized_object),
            self::SERIALIZED_OBJECT_ARRAY_PROPERTY => new ArrayCast(CastType::serialized_object),
            self::JSON_PROPERTY                    => new Cast(CastType::json),
            self::JSON_ARRAY_PROPERTY              => new ArrayCast(CastType::json),
            self::JSON_OBJECT_PROPERTY             => new Cast(CastType::json_object),
            self::JSON_OBJECT_ARRAY_PROPERTY       => new ArrayCast(CastType::json_object),
            self::TRUE_PROPERTY                    => new Cast(CastType::true),
            self::TRUE_ARRAY_PROPERTY              => new ArrayCast(CastType::true),
            self::FALSE_PROPERTY                   => new Cast(CastType::false),
            self::FALSE_ARRAY_PROPERTY             => new ArrayCast(CastType::false),
            self::NULL_PROPERTY                    => new Cast(CastType::null),
            self::NULL_ARRAY_PROPERTY              => new ArrayCast(CastType::null),
            self::MODEL_PROPERTY                   => new OriginalCast(Model::class),
            self::MODEL_ARRAY_PROPERTY             => new OriginalArrayCast(Model::class),
            self::ENUM_PROPERTY                    => new OriginalCast(Enum::class),
            self::ENUM_ARRAY_PROPERTY              => new OriginalArrayCast(Enum::class),
            self::STRING_ENUM_PROPERTY             => new OriginalCast(StringEnum::class),
            self::STRING_ENUM_ARRAY_PROPERTY       => new OriginalArrayCast(StringEnum::class),
            self::INT_ENUM_PROPERTY                => new OriginalCast(IntEnum::class),
            self::INT_ENUM_ARRAY_PROPERTY          => new OriginalArrayCast(IntEnum::class),
        ];
    }
}
