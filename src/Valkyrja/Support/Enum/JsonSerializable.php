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

namespace Valkyrja\Support\Enum;

/**
 * Trait JsonSerializable.
 *
 * @author Melech Mizrachi
 */
trait JsonSerializable
{
    /**
     * Create from json.
     *
     * @param string|int $value The value
     *
     * @return static|null
     */
    public static function fromJson(string|int $value): ?static
    {
        return static::tryFrom($value);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string|int
    {
        return $this->value;
    }
}
