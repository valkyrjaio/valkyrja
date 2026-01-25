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

namespace Valkyrja\Validation\Validator;

use Override;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Throwable\Exception\ValidationException;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;

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
    public function rules(array|null $rules = null): bool
    {
        $rules ??= $this->rules;

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
    public function getFirstErrorMessage(): string|null
    {
        if (! empty($errorMessages = $this->errorMessages)) {
            return $errorMessages[array_key_first($errorMessages)];
        }

        return null;
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
        } catch (ValidationException $validationException) {
            $this->errorMessages[$subject] = "$subject: " . $validationException->getMessage();
        }
    }
}
