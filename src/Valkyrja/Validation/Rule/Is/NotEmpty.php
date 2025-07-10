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

/**
 * Class NotEmpty.
 *
 * @author Melech Mizrachi
 */
class NotEmpty extends Rule
{
    #[Override]
    public function isValid(): bool
    {
        /** @var mixed $subject */
        $subject = $this->subject;

        return $subject !== null
            && $subject !== ''
            && ! empty($subject);
    }

    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return 'Must not be empty';
    }
}
