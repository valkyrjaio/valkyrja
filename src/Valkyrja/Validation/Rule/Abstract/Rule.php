<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Validation\Rule\Abstract;

use Override;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Throwable\Exception\ValidationRuleFailureException;

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
            $this->throwException();
        }
    }

    /**
     * Throw a validation rule failure exception with the error message.
     */
    protected function throwException(): void
    {
        throw new ValidationRuleFailureException($this->errorMessage);
    }
}
