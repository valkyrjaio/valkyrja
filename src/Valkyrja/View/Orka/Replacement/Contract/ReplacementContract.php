<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\View\Orka\Replacement\Contract;

interface ReplacementContract
{
    /**
     * @return non-empty-string
     */
    public function regex(): string;

    /**
     * @return non-empty-string
     */
    public function replacement(): string;
}
