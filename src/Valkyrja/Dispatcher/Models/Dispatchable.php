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
use Valkyrja\Support\Model\Traits\ModelTrait;

use function get_object_vars;

/**
 * Trait Dispatchable.
 *
 * @author Melech Mizrachi
 */
trait Dispatchable
{
    use ModelTrait;

    /**
     * The id.
     *
     * @var string|null
     */
    protected ?string $id = null;

    /**
     * The name.
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * The class.
     *
     * @var string|null
     */
    protected ?string $class = null;

    /**
     * The property.
     *
     * @var string|null
     */
    protected ?string $property = null;

    /**
     * The method.
     *
     * @var string|null
     */
    protected ?string $method = null;

    /**
     * Whether the property or method is static.
     *
     * @var bool
     */
    protected bool $static = false;

    /**
     * The function.
     *
     * @var string|null
     */
    protected ?string $function = null;

    /**
     * The matches.
     *
     * @var array|null
     */
    protected ?array $matches = null;

    /**
     * The closure.
     *
     * @var Closure|null
     */
    protected ?Closure $closure = null;

    /**
     * The dependencies.
     *
     * @var string[]|null
     */
    protected ?array $dependencies = null;

    /**
     * The arguments.
     *
     * @var array|null
     */
    protected ?array $arguments = null;

    /**
     * Get the id.
     *
     * @return string
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
     * @return string
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
     * @return string
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
        $this->class = $class;

        return $this;
    }

    /**
     * Get the property.
     *
     * @return string
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
        $this->property = $property;

        return $this;
    }

    /**
     * Get the method.
     *
     * @return string
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
        $this->method = $method;

        return $this;
    }

    /**
     * Get whether the member is static.
     *
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->static;
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
     * @return string
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
        $this->function = $function;

        return $this;
    }

    /**
     * Get the closure.
     *
     * @return Closure
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
        $this->closure = $closure;

        return $this;
    }

    /**
     * Get the matches.
     *
     * @return array
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
     * @return array
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
     * @return string[]
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

        $array['arguments'] = null;
        $array['closure']   = null;

        return $array;
    }
}
