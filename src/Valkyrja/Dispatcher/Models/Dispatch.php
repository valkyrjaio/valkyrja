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

namespace Valkyrja\Dispatcher\Models;

use Closure;
use Valkyrja\Dispatcher\Dispatch as Contract;
use Valkyrja\Model\Models\Model;

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
    protected string|null $id;

    /**
     * The name.
     *
     * @var string|null
     */
    protected string|null $name;

    /**
     * The class.
     *
     * @var class-string|null
     */
    protected string|null $class;

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
    protected string|null $property;

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
    protected string|null $method;

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
    protected string|null $function;

    /**
     * Whether this is a function dispatch.
     *
     * @var bool
     */
    protected bool $isFunction;

    /**
     * The matches.
     *
     * @var array|null
     */
    protected array|null $matches;

    /**
     * The closure.
     *
     * @var Closure|null
     */
    protected Closure|null $closure;

    /**
     * Whether this is a closure dispatch.
     *
     * @var bool
     */
    protected bool $isClosure;

    /**
     * The dependencies.
     *
     * @var string[]|null
     */
    protected array|null $dependencies;

    /**
     * The arguments.
     *
     * @var array|null
     */
    protected array|null $arguments;

    /**
     * @inheritDoc
     */
    public function getId(): string|null
    {
        return $this->id ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setId(string|null $id = null): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string|null
    {
        return $this->name ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setName(string|null $name = null): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getClass(): string|null
    {
        return $this->class ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setClass(string|null $class = null): static
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
    public function getProperty(): string|null
    {
        return $this->property ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setProperty(string|null $property = null): static
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
    public function getMethod(): string|null
    {
        return $this->method ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setMethod(string|null $method = null): static
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
    public function getFunction(): string|null
    {
        return $this->function ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setFunction(string|null $function = null): static
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
    public function getClosure(): Closure|null
    {
        return $this->closure ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setClosure(Closure|null $closure = null): static
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
    public function getMatches(): array|null
    {
        return $this->matches ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setMatches(array|null $matches = null): static
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): array|null
    {
        return $this->arguments ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setArguments(array|null $arguments = null): static
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): array|null
    {
        return $this->dependencies ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setDependencies(array|null $dependencies = null): static
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function __removeInternalProperties(array &$properties): void
    {
        parent::__removeInternalProperties($properties);

        unset($properties['arguments'], $properties['closure'], $properties['matches']);
    }
}
