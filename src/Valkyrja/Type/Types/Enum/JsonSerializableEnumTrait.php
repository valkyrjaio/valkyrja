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

namespace Valkyrja\Type\Types\Enum;

/**
 * Trait JsonSerializableEnumTrait.
 *
 * @author Melech Mizrachi
 */
trait JsonSerializableEnumTrait
{
    use JsonSerializable;

    /**
     * @inheritDoc
     */
    public static function fromJson(string|int $value): static
    {
        return static::from($value);
    }

    /**
     * @inheritDoc
     */
    public static function tryFromJson(string|int $value): static|null
    {
        return static::tryFrom($value);
    }
}
