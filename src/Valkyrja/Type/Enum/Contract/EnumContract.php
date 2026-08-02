<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Enum\Contract;

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
     */
    #[Override]
    public function asFlatValue(): string|int;
}
