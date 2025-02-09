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

namespace Valkyrja\Dispatcher\Model\Contract;

use Closure;
use Valkyrja\Type\Model\Contract\Model;

/**
 * Interface Dispatch.
 *
 * @author Melech Mizrachi
 */
interface Dispatch extends Model
{
    /**
     * Get the id.
     *
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * Set the id.
     *
     * @param string|null $id The id
     *
     * @return static
     */
    public function setId(?string $id = null): static;

    /**
     * Get the name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set the name.
     *
     * @param string|null $name The name
     *
     * @return static
     */
    public function setName(?string $name = null): static;

    /**
     * Get the class.
     *
     * @return class-string|null
     */
    public function getClass(): ?string;

    /**
     * Set the class.
     *
     * @param class-string|null $class The class
     *
     * @return static
     */
    public function setClass(?string $class = null): static;

    /**
     * Check whether this is a class dispatch.
     *
     * @return bool
     */
    public function isClass(): bool;

    /**
     * Get the property.
     *
     * @return non-empty-string|null
     */
    public function getProperty(): ?string;

    /**
     * Set the property.
     *
     * @param non-empty-string|null $property The property
     *
     * @return static
     */
    public function setProperty(?string $property = null): static;

    /**
     * Check whether this is a class/property dispatch.
     *
     * @return bool
     */
    public function isProperty(): bool;

    /**
     * Get the method.
     *
     * @return non-empty-string|null
     */
    public function getMethod(): ?string;

    /**
     * Set the method.
     *
     * @param non-empty-string|null $method The method
     *
     * @return static
     */
    public function setMethod(?string $method = null): static;

    /**
     * Check whether this is a class/method dispatch.
     *
     * @return bool
     */
    public function isMethod(): bool;

    /**
     * Get whether the member is static.
     *
     * @return bool
     */
    public function isStatic(): bool;

    /**
     * Set whether the member is static.
     *
     * @param bool $static Whether the member is static
     *
     * @return static
     */
    public function setStatic(bool $static = true): static;

    /**
     * @return callable-string|null
     */
    public function getFunction(): ?string;

    /**
     * Set the function.
     *
     * @param callable-string|null $function The function
     *
     * @return static
     */
    public function setFunction(?string $function = null): static;

    /**
     * Check whether this is a function dispatch.
     *
     * @return bool
     */
    public function isFunction(): bool;

    /**
     * Get the closure.
     *
     * @return Closure|null
     */
    public function getClosure(): ?Closure;

    /**
     * Set the closure.
     *
     * @param Closure|null $closure The closure
     *
     * @return static
     */
    public function setClosure(?Closure $closure = null): static;

    /**
     * Check whether this is a closure dispatch.
     *
     * @return bool
     */
    public function isClosure(): bool;

    /**
     * Get the constant.
     *
     * @return non-empty-string|null
     */
    public function getConstant(): ?string;

    /**
     * Set the constant.
     *
     * @param non-empty-string|null $constant The constant
     *
     * @return static
     */
    public function setConstant(?string $constant = null): static;

    /**
     * Check whether this is a constant dispatch.
     *
     * @return bool
     */
    public function isConstant(): bool;

    /**
     * Get the variable.
     *
     * @return non-empty-string|null
     */
    public function getVariable(): ?string;

    /**
     * Set the variable.
     *
     * @param non-empty-string|null $variable The variable
     *
     * @return static
     */
    public function setVariable(?string $variable = null): static;

    /**
     * Check whether this is a variable dispatch.
     *
     * @return bool
     */
    public function isVariable(): bool;

    /**
     * Get the matches.
     *
     * @return array<array-key, mixed>|null
     */
    public function getMatches(): ?array;

    /**
     * Set the matches.
     *
     * @param array<array-key, mixed>|null $matches The matches
     *
     * @return static
     */
    public function setMatches(?array $matches = null): static;

    /**
     * Get the arguments.
     *
     * @return array<array-key, mixed>|null
     */
    public function getArguments(): ?array;

    /**
     * Set the arguments.
     *
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return static
     */
    public function setArguments(?array $arguments = null): static;

    /**
     * Get the dependencies.
     *
     * @return string[]|null
     */
    public function getDependencies(): ?array;

    /**
     * Set the dependencies.
     *
     * @param string[]|null $dependencies The dependencies
     *
     * @return static
     */
    public function setDependencies(?array $dependencies = null): static;
}
