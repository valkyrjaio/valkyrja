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

namespace Valkyrja\Orm\Entities;

use BackedEnum;
use JsonSerializable;

/**
 * Trait EnhancedEnumSupportEntityTrait.
 *
 * @author Melech Mizrachi
 */
trait EnhancedEnumSupportEntityTrait
{
    /**
     * @inheritDoc
     */
    protected function __getEnumValueForDataStore(string $property, mixed $value): string|int
    {
        if ($this->__isValidEnumValue($value)) {
            return $value;
        }

        if ($value instanceof JsonSerializable) {
            return $value->jsonSerialize();
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return serialize($value);
    }
}
