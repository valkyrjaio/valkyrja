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
use Valkyrja\Support\Model\Classes\Model;

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
    protected static bool $setOriginalPropertiesFromArray = false;

    /**
     * The id.
     *
     * @var string|null
     */
    public ?string $id;

    /**
     * The name.
     *
     * @var string|null
     */
    public ?string $name;

    /**
     * The class.
     *
     * @var string|null
     */
    public ?string $class;

    /**
     * Whether this is a class dispatch.
     *
     * @var bool
     */
    public bool $isClass;

    /**
     * The property.
     *
     * @var string|null
     */
    public ?string $property;

    /**
     * Whether this is a class/property dispatch.
     *
     * @var bool
     */
    public bool $isProperty;

    /**
     * The method.
     *
     * @var string|null
     */
    public ?string $method;

    /**
     * Whether this is a class/method dispatch.
     *
     * @var bool
     */
    public bool $isMethod;

    /**
     * Whether the property or method is static.
     *
     * @var bool
     */
    public bool $static;

    /**
     * The function.
     *
     * @var string|null
     */
    public ?string $function;

    /**
     * Whether this is a function dispatch.
     *
     * @var bool
     */
    public bool $isFunction;

    /**
     * The matches.
     *
     * @var array|null
     */
    public ?array $matches;

    /**
     * The closure.
     *
     * @var Closure|null
     */
    public ?Closure $closure;

    /**
     * Whether this is a closure dispatch.
     *
     * @var bool
     */
    public bool $isClosure;

    /**
     * The dependencies.
     *
     * @var string[]|null
     */
    public ?array $dependencies;

    /**
     * The arguments.
     *
     * @var array|null
     */
    public ?array $arguments;

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
    public function setId(string $id = null): static
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
    public function setName(string $name = null): static
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
    public function setClass(string $class = null): static
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
    public function setProperty(string $property = null): static
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
    public function setMethod(string $method = null): static
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
    public function setFunction(string $function = null): static
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
    public function setClosure(Closure $closure = null): static
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
    public function setMatches(array $matches = null): static
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
    public function setArguments(array $arguments = null): static
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
    public function setDependencies(array $dependencies = null): static
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $array = get_object_vars($this);

        unset($array['arguments'], $array['closure']);

        return $array;
    }
}
