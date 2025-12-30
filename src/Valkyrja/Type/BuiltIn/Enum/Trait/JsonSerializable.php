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

namespace Valkyrja\Type\BuiltIn\Enum\Trait;

use BackedEnum;

/**
 * Trait JsonSerializable.
 *
 * @author Melech Mizrachi
 */
trait JsonSerializable
{
    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string|int
    {
        if ($this instanceof BackedEnum) {
            return $this->value;
        }

        return $this->name;
    }
}
