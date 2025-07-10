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

namespace Valkyrja\Type\BuiltIn\Enum\Contract;

use JsonSerializable as BuiltInJsonSerializable;
use Override;
use UnitEnum;

/**
 * Interface JsonSerializable.
 *
 * @author Melech Mizrachi
 */
interface JsonSerializable extends BuiltInJsonSerializable, UnitEnum
{
    /**
     * Json serialize.
     *
     * @return string|int
     */
    #[Override]
    public function jsonSerialize(): string|int;
}
