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

namespace Valkyrja\Validation\Rule\Abstract;

use Override;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

abstract class Rule implements RuleContract
{
    /**
     * @param non-empty-string $errorMessage The error message
     */
    public function __construct(
        protected mixed $subject,
        protected string $errorMessage
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSubject(): mixed
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function validate(): void
    {
        if (! $this->isValid()) {
            $this->getException();
        }
    }

    /**
     * Get the exception.
     */
    protected function getException(): void
    {
        throw new ValidationException($this->errorMessage);
    }
}
