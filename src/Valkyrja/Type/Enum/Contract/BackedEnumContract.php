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

use BackedEnum;
use Override;
use Valkyrja\Type\Contract\TypeContract;

/**
 * @extends TypeContract<static>
 */
interface BackedEnumContract extends TypeContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): BackedEnum;
}
