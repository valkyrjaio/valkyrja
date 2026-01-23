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

namespace Valkyrja\View\Orka\Replacement\Variable;

use Override;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

class SetVariable implements ReplacementContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function regex(): string
    {
        return '/@setvariable\s*\(\s*(.*)\s*,(.*)\s*\)/x';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function replacement(): string
    {
        return '<?php $template->setVariable(${1}, ${2}); ?>';
    }
}
