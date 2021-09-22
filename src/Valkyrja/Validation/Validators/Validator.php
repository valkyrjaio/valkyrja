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

namespace Valkyrja\Validation\Validators;

use Exception;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Container\Container;
use Valkyrja\Validation\Validator as Contract;

/**
 * Class Validator.
 *
 * @author Melech Mizrachi
 */
class Validator implements Contract
{
    /**
     * The rules.
     *
     * @var object[]
     */
    protected static array $rules = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The default rules.
     *
     * @var string
     */
    protected string $defaultRules;

    /**
     * The error messages if validation failed.
     *
     * @var array
     */
    protected array $errorMessages = [];

    /**
     * The validation rules.
     *
     * @var array|null
     */
    protected ?array $validationRules = null;

    /**
     * Validator constructor.
     *
     * @param Container $container
     * @param array     $config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container    = $container;
        $this->config       = $config;
        $this->defaultRules = $config['rule'];
    }

    /**
     * @inheritDoc
     */
    public function getRules(string $name = null)
    {
        $name ??= $this->defaultRules;

        return self::$rules[$name]
            ?? self::$rules[$name] = $this->container->get($name);
    }

    /**
     * @inheritDoc
     */
    public function validate(): bool
    {
        $validated = true;

        if ($this->validationRules) {
            $validated = $this->validateRules($this->validationRules);
        }

        return $validated;
    }

    /**
     * @inheritDoc
     */
    public function validateRules(array $rules): bool
    {
        $this->validateRuleSet($rules);

        return empty($this->errorMessages);
    }

    /**
     * @inheritDoc
     */
    public function setRules(array $rules): void
    {
        $this->validationRules = $rules;
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * @inheritDoc
     */
    public function getFirstErrorMessage(): ?string
    {
        if (! empty($errorMessages = $this->errorMessages)) {
            return $errorMessages[array_key_first($errorMessages)];
        }

        return null;
    }

    /**
     * Validate a rule set.
     *
     * @param array $ruleSet The rule set
     *
     * @return void
     */
    protected function validateRuleSet(array $ruleSet): void
    {
        foreach ($ruleSet as $key => $item) {
            $this->validateSubject($key, $item['subject'] ?? null, $item['rules'] ?? []);
        }
    }

    /**
     * Validate a subject item.
     *
     * @param string $subjectName The subject name
     * @param mixed  $subject     The subject
     * @param array  $rules       The rules
     *
     * @return void
     */
    protected function validateSubject(string $subjectName, $subject, array $rules = []): void
    {
        foreach ($rules as $name => $rule) {
            if (isset($this->errorMessages[$subjectName])) {
                continue;
            }

            $this->validateRule($subjectName, $name, $subject, $rule);
        }
    }

    /**
     * Validate a rule.
     *
     * @param string $subjectName The subject name
     * @param string $name        The rule name
     * @param mixed  $subject     The subject
     * @param array  $rule        The rule
     *
     * @return void
     */
    protected function validateRule(string $subjectName, string $name, $subject, array $rule): void
    {
        $arguments    = $rule['arguments'] ?? [];
        $rulesName    = $this->config[CKP::RULES_MAP][$name] ?? null;
        $errorMessage = $rule['errorMessage'] ?? null;
        // Not in try catch to avoid swallowing an error if rule doesn't exist
        $rules = $this->getRules($rulesName);

        try {
            $rules->{$name}($subject, ...$arguments);
        } catch (Exception $exception) {
            $this->errorMessages[$subjectName] = $errorMessage ?? $exception->getMessage();
        }
    }
}
