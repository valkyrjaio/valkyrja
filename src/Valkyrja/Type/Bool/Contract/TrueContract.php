<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Bool\Contract;

use Override;
use Valkyrja\Type\Contract\TypeContract;

/**
 * @extends TypeContract<true>
 */
interface TrueContract extends TypeContract
{
    /**
     * @inheritDoc
     *
     * @return true
     */
    #[Override]
    public function asValue(): bool;

    /**
     * @inheritDoc
     *
     * @return true
     */
    #[Override]
    public function asFlatValue(): bool;
}
