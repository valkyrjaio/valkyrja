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

namespace Valkyrja\Type;

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
     * Json serialize.
     *
     * @return string|int
     */
    public function jsonSerialize(): string|int;
}
