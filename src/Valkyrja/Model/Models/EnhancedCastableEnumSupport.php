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

namespace Valkyrja\Model\Models;

use BackedEnum;
use UnitEnum;
use Valkyrja\Type\Enum\JsonSerializableEnum;

/**
 * Trait EnhancedCastableEnumSupport.
 *
 * @author Melech Mizrachi
 */
trait EnhancedCastableEnumSupport
{
    use EnhancedEnumSupport;

    /**
     * @inheritDoc
     */
    protected function __getEnumFromValue(string $property, string $type, mixed $value): UnitEnum
    {
        // If it's already an enum just send it along the way
        if ($value instanceof UnitEnum) {
            return $value;
        }

        if (is_a($type, JsonSerializableEnum::class, true)) {
            return $type::fromJson($value);
        }

        if (is_a($type, BackedEnum::class, true)) {
            return $type::from($value);
        }

        return unserialize(
            $value,
            [
                'allowed_classes' => [$type],
            ]
        );
    }
}
