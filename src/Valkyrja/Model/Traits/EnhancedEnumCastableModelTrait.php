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
use UnitEnum;
use Valkyrja\Support\Enum\JsonSerializableEnum;
use Valkyrja\Support\Type\Cls;

/**
 * Trait EnhancedEnumSupportCastableModelTrait.
 *
 * @author Melech Mizrachi
 */
trait EnhancedEnumCastableModelTrait
{
    use EnhancedEnumModelTrait;

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
}
