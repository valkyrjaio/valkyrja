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

namespace Valkyrja\Validation\Rule\Int;

use Override;
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_int;

class GreaterThan extends Rule
{
    /**
     * @param non-empty-string $errorMessage The error message
     */
    public function __construct(
        mixed $subject,
        protected int $min,
        string $errorMessage
    ) {
        parent::__construct($subject, $errorMessage);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isValid(): bool
    {
        return is_int($this->subject)
            && $this->subject > $this->min;
    }
}
