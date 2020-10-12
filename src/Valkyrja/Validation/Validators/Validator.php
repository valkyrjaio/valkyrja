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
        $this->defaultRules = $config['rule'] ?? CKP::DEFAULT;
    }

    /**
     * Get a rule set by name.
     *
     * @param string|null $name [optional] The name of the rules to get
     *
     * @return mixed
     */
    public function getRules(string $name = null)
    {
        $rules = $this->config[CKP::RULES][$name]
            ?? $this->config[CKP::RULES][$this->defaultRules];

        return self::$rules[$name]
            ?? self::$rules[$name] = $this->container->get($rules);
    }

    /**
     * Validate against set rules.
     *
     * @return bool
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
     * Validate a set of rules.
     *
     * @param array ...$rules The rules
     *
     * @return bool
     */
    public function validateRules(array $rules): bool
    {
        $this->validateRuleSet($rules);

        return empty($this->errorMessages);
    }

    /**
     * Set the rules to validate.
     *
     * @param array ...$rules The rules
     *
     * @return void
     */
    public function setRules(array $rules): void
    {
        $this->validationRules = $rules;
    }

    /**
     * Get the error messages.
     *
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * Get the last error message thrown.
     *
     * @return string|null
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
            $this->validateSubject($item['subject'] ?? null, $item['rules'] ?? []);
        }
    }

    /**
     * Validate a subject item.
     *
     * @param mixed $subject The subject
     * @param array $rules   The rules
     *
     * @return void
     */
    protected function validateSubject($subject, array $rules = []): void
    {
        foreach ($rules as $name => $rule) {
            $this->validateRule($name, $subject, $rule);
        }
    }

    /**
     * Validate a rule.
     *
     * @param string $name    The rule name
     * @param mixed  $subject The subject
     * @param array  $rule    The rule
     *
     * @return void
     */
    protected function validateRule(string $name, $subject, array $rule): void
    {
        $arguments    = $rule['arguments'] ?? [];
        $rulesName    = $this->config[CKP::RULES_MAP][$name] ?? null;
        $errorMessage = $rule['errorMessage'] ?? null;
        // Not in try catch to avoid swallowing an error if rule doesn't exist
        $rules = $this->getRules($rulesName);

        try {
            $rules->{$name}($subject, ...$arguments);
        } catch (Exception $exception) {
            $this->errorMessages[$name] = $errorMessage ?? $exception->getMessage();
        }
    }
}
