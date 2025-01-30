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

use UnitEnum;
use Valkyrja\Type\Contract\Type;

/**
 * Interface Enum.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<static>
 */
interface Enum extends Type, UnitEnum
{
    /**
     * @inheritDoc
     */
    public function asValue(): static;

    /**
     * @inheritDoc
     *
     * @return string|int
     */
    public function asFlatValue(): string|int;
}
