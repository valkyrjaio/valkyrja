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

use Override;
use UnitEnum;
use Valkyrja\Type\Contract\TypeContract;

/**
 * @extends TypeContract<static>
 */
interface EnumContract extends TypeContract, UnitEnum
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): static;

    /**
     * @inheritDoc
     *
     * @return string|int
     */
    #[Override]
    public function asFlatValue(): string|int;
}
