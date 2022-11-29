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
     * @var string|null
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
     * @var string|null
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
     * @var string|null
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
     * @var string|null
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
     * @var array|null
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
     * The dependencies.
     *
     * @var string[]|null
     */
    protected ?array $dependencies;

    /**
     * The arguments.
     *
     * @var array|null
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
    public function setId(string $id = null): self
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
    public function setName(string $name = null): self
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
    public function setClass(string $class = null): self
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
    public function setProperty(string $property = null): self
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
    public function setMethod(string $method = null): self
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
    public function setStatic(bool $static = true): self
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
    public function setFunction(string $function = null): self
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
    public function setClosure(Closure $closure = null): self
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
    public function getMatches(): ?array
    {
        return $this->matches ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setMatches(array $matches = null): self
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
    public function setArguments(array $arguments = null): self
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
    public function setDependencies(array $dependencies = null): self
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function __allProperties(bool $includeHidden = false): array
    {
        return $this->__allPropertiesIncludingHidden();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $array = $this->__allPropertiesIncludingHidden();

        unset($array['arguments'], $array['closure']);

        return $array;
    }
}
