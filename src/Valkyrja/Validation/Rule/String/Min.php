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

namespace Valkyrja\Validation\Rule\String;

use Override;
use Valkyrja\Type\String\Factory\StringFactory;
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_string;

class Min extends Rule
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
        if (! is_string($this->subject)) {
            return false;
        }

        return StringFactory::min($this->subject, $this->min);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDefaultErrorMessage(): string
    {
        return "Must be longer than $this->min";
    }
}
