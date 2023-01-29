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

namespace Valkyrja\Type\Enum;

use JsonSerializable;
use UnitEnum;

/**
 * Interface JsonSerializable.
 *
 * @author Melech Mizrachi
 */
interface JsonSerializableEnum extends JsonSerializable, UnitEnum
{
    /**
     * Create from json.
     *
     * @param string|int $value The value
     *
     * @return static
     */
    public static function fromJson(string|int $value): static;

    /**
     * Try to create from json.
     *
     * @param string|int $value The value
     *
     * @return static|null
     */
    public static function tryFromJson(string|int $value): ?static;

    /**
     * Json serialize.
     *
     * @return string|int
     */
    public function jsonSerialize(): string|int;
}
