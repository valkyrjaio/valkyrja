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
use Valkyrja\Validation\Rule\Abstract\Rule;

class Equal extends Rule
{
    /**
     * @param non-empty-string|null $errorMessage The error message
     */
    public function __construct(
        mixed $subject,
        protected mixed $value,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    #[Override]
    public function isValid(): bool
    {
        return $this->subject === $this->value;
    }

    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return 'Must equal';
    }
}
