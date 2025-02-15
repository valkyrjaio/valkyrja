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

namespace Valkyrja\Dispatcher\Model;

use Closure;
use Valkyrja\Dispatcher\Model\Contract\Dispatch as Contract;
use Valkyrja\Type\Model\Model;

/**
 * Class Dispatch.
 *
 * @author Melech Mizrachi
 */
class Dispatch extends Model implements Contract
{
    /**
     * @inheritDoc
     */
    protected static bool $shouldSetOriginalProperties = false;

    /**
     * The id.
     *
     * @var string|null
     */
    protected ?string $id;

    /**
     * The name.
     *
     * @var string|null
     */
    protected ?string $name;

    /**
     * The class.
     *
     * @var class-string|null
     */
    protected ?string $class;

    /**
     * Whether this is a class dispatch.
     *
     * @var bool
     */
    protected bool $isClass;

    /**
     * The property.
     *
     * @var non-empty-string|null
     */
    protected ?string $property;

    /**
     * Whether this is a class/property dispatch.
     *
     * @var bool
     */
    protected bool $isProperty;

    /**
     * The method.
     *
     * @var non-empty-string|null
     */
    protected ?string $method;

    /**
     * Whether this is a class/method dispatch.
     *
     * @var bool
     */
    protected bool $isMethod;

    /**
     * Whether the property or method is static.
     *
     * @var bool
     */
    protected bool $static;

    /**
     * The function.
     *
     * @var callable-string|null
     */
    protected ?string $function;

    /**
     * Whether this is a function dispatch.
     *
     * @var bool
     */
    protected bool $isFunction;

    /**
     * The matches.
     *
     * @var array<array-key, mixed>|null
     */
    protected ?array $matches;

    /**
     * The closure.
     *
     * @var Closure|null
     */
    protected ?Closure $closure;

    /**
     * Whether this is a closure dispatch.
     *
     * @var bool
     */
    protected bool $isClosure;

    /**
     * The constant.
     *
     * @var non-empty-string|null
     */
    protected ?string $constant;

    /**
     * Whether this is a constant dispatch.
     *
     * @var bool
     */
    protected bool $isConstant;

    /**
     * The variable.
     *
     * @var non-empty-string|null
     */
    protected ?string $variable;

    /**
     * Whether this is a variable dispatch.
     *
     * @var bool
     */
    protected bool $isVariable;

    /**
     * The dependencies.
     *
     * @var string[]|null
     */
    protected ?array $dependencies;

    /**
     * The arguments.
     *
     * @var array<array-key, mixed>|null
     */
    protected ?array $arguments;

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setId(?string $id = null): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setName(?string $name = null): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getClass(): ?string
    {
        return $this->class ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setClass(?string $class = null): static
    {
        $this->class   = $class;
        $this->isClass = $class !== null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isClass(): bool
    {
        return $this->isClass ?? false;
    }

    /**
     * @inheritDoc
     */
    public function getProperty(): ?string
    {
        return $this->property ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setProperty(?string $property = null): static
    {
        $this->property   = $property;
        $this->isProperty = $property !== null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isProperty(): bool
    {
        return $this->isProperty ?? false;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): ?string
    {
        return $this->method ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setMethod(?string $method = null): static
    {
        $this->method   = $method;
        $this->isMethod = $method !== null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isMethod(): bool
    {
        return $this->isMethod ?? false;
    }

    /**
     * @inheritDoc
     */
    public function isStatic(): bool
    {
        return $this->static ?? false;
    }

    /**
     * @inheritDoc
     */
    public function setStatic(bool $static = true): static
    {
        $this->static = $static;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFunction(): ?string
    {
        return $this->function ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setFunction(?string $function = null): static
    {
        $this->function   = $function;
        $this->isFunction = $function !== null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isFunction(): bool
    {
        return $this->isFunction ?? false;
    }

    /**
     * @inheritDoc
     */
    public function getClosure(): ?Closure
    {
        return $this->closure ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setClosure(?Closure $closure = null): static
    {
        $this->closure   = $closure;
        $this->isClosure = $closure !== null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isClosure(): bool
    {
        return $this->isClosure ?? false;
    }

    /**
     * @inheritDoc
     */
    public function getConstant(): ?string
    {
        return $this->constant ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setConstant(?string $constant = null): static
    {
        $this->constant   = $constant;
        $this->isConstant = $constant !== null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isConstant(): bool
    {
        return $this->isConstant ?? false;
    }

    /**
     * @inheritDoc
     */
    public function getVariable(): ?string
    {
        return $this->variable ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setVariable(?string $variable = null): static
    {
        $this->variable   = $variable;
        $this->isVariable = $variable !== null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isVariable(): bool
    {
        return $this->isVariable ?? false;
    }

    /**
     * @inheritDoc
     */
    public function getMatches(): ?array
    {
        return $this->matches ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setMatches(?array $matches = null): static
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): ?array
    {
        return $this->arguments ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setArguments(?array $arguments = null): static
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): ?array
    {
        return $this->dependencies ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setDependencies(?array $dependencies = null): static
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function internalRemoveInternalProperties(array &$properties): void
    {
        parent::internalRemoveInternalProperties($properties);

        // Removing arguments because they are specific to the current dispatch call,
        // and different arguments might be used for different calls.
        // Removing closure because that cannot be cached properly
        // Removing matches as that is specific to the current call,
        // and different matches might be used for different calls.
        unset($properties['arguments'], $properties['closure'], $properties['matches']);
    }
}
