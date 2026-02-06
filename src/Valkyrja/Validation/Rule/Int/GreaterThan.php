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
     * @param non-empty-string|null $errorMessage The error message
     */
    public function __construct(
        mixed $subject,
        protected int $min,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    #[Override]
    public function isValid(): bool
    {
        return is_int($this->subject)
            && $this->subject > $this->min;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return "Must be greater than $this->min";
    }
}
