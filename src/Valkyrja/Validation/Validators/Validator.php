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
use Valkyrja\Container\Support\Provides;
use Valkyrja\Validation\Exceptions\ValidationException;
use Valkyrja\Validation\Validator as Contract;

/**
 * Class Validator.
 *
 * @author Melech Mizrachi
 */
class Validator implements Contract
{
    use Provides;

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
     * The default error message.
     *
     * @var string
     */
    protected string $defaultErrorMessage = 'Validation failed.';

    /**
     * The error message if validation failed.
     *
     * @var string|null
     */
    protected ?string $errorMessage = null;

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
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Contract::class,
            new static(
                $container,
                (array) $config['validation']
            )
        );
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
            $validated = $this->validateRules(...$this->validationRules);
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
    public function validateRules(array ...$rules): bool
    {
        try {
            $this->validateRuleSet($rules);
        } catch (Exception $exception) {
            $this->errorMessage = $exception->getMessage();

            return false;
        }

        return true;
    }

    /**
     * Set the rules to validate.
     *
     * @param array ...$rules The rules
     *
     * @return void
     */
    public function setRules(array ...$rules): void
    {
        $this->validationRules = $rules;
    }

    /**
     * Get the last error message thrown.
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * Set the default error message.
     *
     * @param string $defaultErrorMessage The default error message
     *
     * @return void
     */
    public function setDefaultErrorMessage(string $defaultErrorMessage): void
    {
        $this->defaultErrorMessage = $defaultErrorMessage;
    }

    /**
     * Validate a rule set.
     *
     * @param array $ruleSet The rule set
     *
     * @throws ValidationException
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
     * @param mixed|null $subject The subject
     * @param array      $rules   The rules
     *
     * @throws ValidationException
     *
     * @return void
     */
    protected function validateSubject($subject = null, array $rules = []): void
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
     * @throws ValidationException
     *
     * @return void
     */
    protected function validateRule(string $name, $subject, array $rule): void
    {
        $arguments    = $rule['arguments'] ?? [];
        $rulesName    = $rule['rules'] ?? null;
        $errorMessage = $rule['errorMessage'] ?? null;

        try {
            $this->getRules($rulesName)->{$name}($subject, ...$arguments);
        } catch (Exception $exception) {
            throw new ValidationException($errorMessage ?? $this->defaultErrorMessage);
        }
    }
}
