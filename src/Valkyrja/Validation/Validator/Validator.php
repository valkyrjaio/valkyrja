<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Validation\Validator;

use Override;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Throwable\Exception\ValidationRuleFailureException;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;

use function array_key_first;

class Validator implements ValidatorContract
{
    /**
     * The error messages if validation failed.
     *
     * @var array<non-empty-string, non-empty-string>
     */
    protected array $errorMessages = [];

    /**
     * @param array<non-empty-string, RuleContract[]> $rules The rules
     */
    public function __construct(
        protected array $rules = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function validateRules(): bool
    {
        $rules = $this->rules;

        foreach ($rules as $subject => $subjectRules) {
            foreach ($subjectRules as $rule) {
                $this->validateRule($rule, $subject);
            }
        }

        return empty($this->errorMessages);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasFirstErrorMessage(): bool
    {
        return $this->errorMessages !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFirstErrorMessage(): string
    {
        $errorMessages = $this->errorMessages;

        if ($errorMessages !== []) {
            return $errorMessages[array_key_first($errorMessages)];
        }

        return '';
    }

    /**
     * Validate a rule for a subject.
     *
     * @param non-empty-string $subject The subject to validate
     */
    protected function validateRule(RuleContract $rule, string $subject): void
    {
        try {
            $rule->validate();
        } catch (ValidationRuleFailureException $validationException) {
            $this->errorMessages[$subject] = "$subject: " . $validationException->getMessage();
        }
    }
}
