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

namespace Valkyrja\Dispatcher;

use Closure;
use Valkyrja\Model\Model;

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
    public function setId(string $id = null): self;

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
    public function setName(string $name = null): self;

    /**
     * Get the class.
     *
     * @return string|null
     */
    public function getClass(): ?string;

    /**
     * Set the class.
     *
     * @param string|null $class The class
     *
     * @return static
     */
    public function setClass(string $class = null): self;

    /**
     * Check whether this is a class dispatch.
     *
     * @return bool
     */
    public function isClass(): bool;

    /**
     * Get the property.
     *
     * @return string|null
     */
    public function getProperty(): ?string;

    /**
     * Set the property.
     *
     * @param string|null $property The property
     *
     * @return static
     */
    public function setProperty(string $property = null): self;

    /**
     * Check whether this is a class/property dispatch.
     *
     * @return bool
     */
    public function isProperty(): bool;

    /**
     * Get the method.
     *
     * @return string|null
     */
    public function getMethod(): ?string;

    /**
     * Set the method.
     *
     * @param string|null $method The method
     *
     * @return static
     */
    public function setMethod(string $method = null): self;

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
    public function setStatic(bool $static = true): self;

    /**
     * @return string|null
     */
    public function getFunction(): ?string;

    /**
     * Set the function.
     *
     * @param string|null $function The function
     *
     * @return static
     */
    public function setFunction(string $function = null): self;

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
    public function setClosure(Closure $closure = null): self;

    /**
     * Check whether this is a closure dispatch.
     *
     * @return bool
     */
    public function isClosure(): bool;

    /**
     * Get the matches.
     *
     * @return array|null
     */
    public function getMatches(): ?array;

    /**
     * Set the matches.
     *
     * @param array|null $matches The matches
     *
     * @return static
     */
    public function setMatches(array $matches = null): self;

    /**
     * Get the arguments.
     *
     * @return array|null
     */
    public function getArguments(): ?array;

    /**
     * Set the arguments.
     *
     * @param array|null $arguments The arguments
     *
     * @return static
     */
    public function setArguments(array $arguments = null): self;

    /**
     * Get the dependencies.
     *
     * @return array|null
     */
    public function getDependencies(): ?array;

    /**
     * Set the dependencies.
     *
     * @param array|null $dependencies The dependencies
     *
     * @return static
     */
    public function setDependencies(array $dependencies = null): self;
}
