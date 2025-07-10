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

namespace Valkyrja\Validation\Rule\Is;

use Override;
use Valkyrja\Validation\Rule\Rule;

use function is_numeric;

/**
 * Class IsNumeric.
 *
 * @author Melech Mizrachi
 */
class IsNumeric extends Rule
{
    #[Override]
    public function isValid(): bool
    {
        return is_numeric($this->subject);
    }

    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return 'Must be numeric';
    }
}
