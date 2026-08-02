<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\View\Orka\Replacement\Partial;

use Override;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

class TrimPartialWithVariables implements ReplacementContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function regex(): string
    {
        return '/@trimpartial\s*\(\s*(.*)\s*,(.*)\s*\)/x';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replacement(): string
    {
        return '<?= trim($template->getPartial(${1}, ${2})); ?>';
    }
}
