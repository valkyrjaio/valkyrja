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
use Valkyrja\Validation\Rule\Contract\Rule;
use Valkyrja\Validation\Throwable\Exception\ValidationException;
use Valkyrja\Validation\Validator\Contract\Validator as Contract;

/**
 * Class Validate.
 *
 * @author Melech Mizrachi
 */
class Validator implements Contract
{
    /**
     * The error messages if validation failed.
     *
     * @var array<string, string>
     */
    protected array $errorMessages = [];

    /**
     * @param array<string, Rule[]> $rules
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
     */
    protected function validateRule(Rule $rule, string $subject): void
    {
        try {
            $rule->validate();
        } catch (ValidationException $validationException) {
            $this->errorMessages[$subject] = "$subject: " . $validationException->getMessage();
        }
    }
}
