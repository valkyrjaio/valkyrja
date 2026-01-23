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

namespace Valkyrja\View\Orka\Replacement\Block;

use Override;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

class Block implements ReplacementContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function regex(): string
    {
        return '/@block\s*\(\s*(.*)\s*\)/x';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replacement(): string
    {
        return '<?= $template->getBlock(${1}); ?>';
    }
}
