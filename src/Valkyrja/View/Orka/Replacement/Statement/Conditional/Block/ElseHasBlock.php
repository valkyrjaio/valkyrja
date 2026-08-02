<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\View\Orka\Replacement\Statement\Conditional\Block;

use Override;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

class ElseHasBlock implements ReplacementContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function regex(): string
    {
        return '/@elsehasblock\s*\(\s*(.*)\s*\)/x';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replacement(): string
    {
        return '<?php elseif ($template->hasBlock(${1})) : ?>';
    }
}
