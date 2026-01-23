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

namespace Valkyrja\View\Orka\Replacement\Statement\Iterate;

use Override;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

class For_ implements ReplacementContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function regex(): string
    {
        return '/@for\s*\(\s*(.*)\s*\)/x';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replacement(): string
    {
        return '<?php for (${1}) : ?>';
    }
}
