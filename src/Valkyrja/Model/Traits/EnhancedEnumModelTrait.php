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

namespace Valkyrja\Model\Traits;

use BackedEnum;
use JsonSerializable;
use UnitEnum;
use Valkyrja\Support\Enum\JsonSerializableEnum;
use Valkyrja\Support\Type\Cls;

/**
 * Trait EnhancedEnumSupportModelTrait.
 *
 * @author Melech Mizrachi
 */
trait EnhancedEnumModelTrait
{
    /**
     * @inheritDoc
     */
    protected function __getEnumFromValue(string $property, string $type, mixed $value): UnitEnum
    {
        // If it's already an enum just send it along the way
        if ($value instanceof UnitEnum) {
            return $value;
        }

        if (Cls::inherits($type, JsonSerializableEnum::class)) {
            /** @var JsonSerializableEnum $type */
            return $type::fromJson($value);
        }

        if (Cls::inherits($type, BackedEnum::class)) {
            /** @var BackedEnum $type */
            return $type::from($value);
        }

        return unserialize(
            $value,
            [
                'allowed_classes' => $type,
            ]
        );
    }

    /**
     * Get a property's value for jsonSerialize.
     *
     * @param string $property The property
     *
     * @return mixed
     */
    protected function __getJsonPropertyValue(string $property): mixed
    {
        $value = $this->__getAsArrayPropertyValue($property);

        // If this is a json array we're building and the value isn't JsonSerializable
        if (! ($value instanceof JsonSerializable)) {
            if ($value instanceof BackedEnum) {
                return $value->value;
            }

            if ($value instanceof UnitEnum) {
                return serialize($value);
            }
        }

        return $value;
    }
}
