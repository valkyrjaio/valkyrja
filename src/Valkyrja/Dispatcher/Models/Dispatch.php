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
     * The id.
     *
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The name.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * The class.
     *
     * @var string|null
     */
    public ?string $class = null;

    /**
     * Whether this is a class dispatch.
     *
     * @var bool
     */
    public bool $isClass = false;

    /**
     * The property.
     *
     * @var string|null
     */
    public ?string $property = null;

    /**
     * Whether this is a class/property dispatch.
     *
     * @var bool
     */
    public bool $isProperty = false;

    /**
     * The method.
     *
     * @var string|null
     */
    public ?string $method = null;

    /**
     * Whether this is a class/method dispatch.
     *
     * @var bool
     */
    public bool $isMethod = false;

    /**
     * Whether the property or method is static.
     *
     * @var bool
     */
    public bool $static = false;

    /**
     * The function.
     *
     * @var string|null
     */
    public ?string $function = null;

    /**
     * Whether this is a function dispatch.
     *
     * @var bool
     */
    public bool $isFunction = false;

    /**
     * The matches.
     *
     * @var array|null
     */
    public ?array $matches = null;

    /**
     * The closure.
     *
     * @var Closure|null
     */
    public ?Closure $closure = null;

    /**
     * Whether this is a closure dispatch.
     *
     * @var bool
     */
    public bool $isClosure = false;

    /**
     * The dependencies.
     *
     * @var string[]|null
     */
    public ?array $dependencies = null;

    /**
     * The arguments.
     *
     * @var array|null
     */
    public ?array $arguments = null;

    /**
     * Get the id.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the id.
     *
     * @param string|null $id The id
     *
     * @return static
     */
    public function setId(string $id = null): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name.
     *
     * @param string|null $name The name
     *
     * @return static
     */
    public function setName(string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the class.
     *
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * Set the class.
     *
     * @param string|null $class The class
     *
     * @return static
     */
    public function setClass(string $class = null): self
    {
        $this->class   = $class;
        $this->isClass = $class !== null;

        return $this;
    }

    /**
     * Check whether this is a class dispatch.
     *
     * @return bool
     */
    public function isClass(): bool
    {
        return $this->isClass ?? false;
    }

    /**
     * Get the property.
     *
     * @return string|null
     */
    public function getProperty(): ?string
    {
        return $this->property;
    }

    /**
     * Set the property.
     *
     * @param string|null $property The property
     *
     * @return static
     */
    public function setProperty(string $property = null): self
    {
        $this->property   = $property;
        $this->isProperty = $property !== null;

        return $this;
    }

    /**
     * Check whether this is a class/property dispatch.
     *
     * @return bool
     */
    public function isProperty(): bool
    {
        return $this->isProperty ?? false;
    }

    /**
     * Get the method.
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * Set the method.
     *
     * @param string|null $method The method
     *
     * @return static
     */
    public function setMethod(string $method = null): self
    {
        $this->method   = $method;
        $this->isMethod = $method !== null;

        return $this;
    }

    /**
     * Check whether this is a class/method dispatch.
     *
     * @return bool
     */
    public function isMethod(): bool
    {
        return $this->isMethod;
    }

    /**
     * Get whether the member is static.
     *
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->static ?? false;
    }

    /**
     * Set whether the member is static.
     *
     * @param bool $static Whether the member is static
     *
     * @return static
     */
    public function setStatic(bool $static = true): self
    {
        $this->static = $static;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFunction(): ?string
    {
        return $this->function;
    }

    /**
     * Set the function.
     *
     * @param string|null $function The function
     *
     * @return static
     */
    public function setFunction(string $function = null): self
    {
        $this->function   = $function;
        $this->isFunction = $function !== null;

        return $this;
    }

    /**
     * Check whether this is a function dispatch.
     *
     * @return bool
     */
    public function isFunction(): bool
    {
        return $this->isFunction ?? false;
    }

    /**
     * Get the closure.
     *
     * @return Closure|null
     */
    public function getClosure(): ?Closure
    {
        return $this->closure;
    }

    /**
     * Set the closure.
     *
     * @param Closure|null $closure The closure
     *
     * @return static
     */
    public function setClosure(Closure $closure = null): self
    {
        $this->closure   = $closure;
        $this->isClosure = $closure !== null;

        return $this;
    }

    /**
     * Check whether this is a closure dispatch.
     *
     * @return bool
     */
    public function isClosure(): bool
    {
        return $this->isClosure ?? false;
    }

    /**
     * Get the matches.
     *
     * @return array|null
     */
    public function getMatches(): ?array
    {
        return $this->matches;
    }

    /**
     * Set the matches.
     *
     * @param array|null $matches The matches
     *
     * @return static
     */
    public function setMatches(array $matches = null): self
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * Get the arguments.
     *
     * @return array|null
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }

    /**
     * Set the arguments.
     *
     * @param array|null $arguments The arguments
     *
     * @return static
     */
    public function setArguments(array $arguments = null): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Get the dependencies.
     *
     * @return string[]|null
     */
    public function getDependencies(): ?array
    {
        return $this->dependencies;
    }

    /**
     * Set the dependencies.
     *
     * @param string[]|null $dependencies The dependencies
     *
     * @return static
     */
    public function setDependencies(array $dependencies = null): self
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * Serialize properties for json_encode.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $array = get_object_vars($this);

        unset($array['arguments'], $array['closure']);

        return $array;
    }
}
