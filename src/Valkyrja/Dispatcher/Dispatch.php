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
use Valkyrja\Support\Model\Model;

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
     * @return string
     */
    public function getId(): ?string;

    /**
     * Set the id.
     *
     * @param string $id The id
     *
     * @return static
     */
    public function setId(string $id = null): self;

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName(): ?string;

    /**
     * Set the name.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function setName(string $name = null): self;

    /**
     * Get the class.
     *
     * @return string
     */
    public function getClass(): ?string;

    /**
     * Set the class.
     *
     * @param string $class The class
     *
     * @return static
     */
    public function setClass(string $class = null): self;

    /**
     * Get the property.
     *
     * @return string
     */
    public function getProperty(): ?string;

    /**
     * Set the property.
     *
     * @param string $property The property
     *
     * @return static
     */
    public function setProperty(string $property = null): self;

    /**
     * Get the method.
     *
     * @return string
     */
    public function getMethod(): ?string;

    /**
     * Set the method.
     *
     * @param string $method The method
     *
     * @return static
     */
    public function setMethod(string $method = null): self;

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
     * @return string
     */
    public function getFunction(): ?string;

    /**
     * Set the function.
     *
     * @param string $function The function
     *
     * @return static
     */
    public function setFunction(string $function = null): self;

    /**
     * Get the closure.
     *
     * @return Closure
     */
    public function getClosure(): ?Closure;

    /**
     * Set the closure.
     *
     * @param Closure $closure The closure
     *
     * @return static
     */
    public function setClosure(Closure $closure = null): self;

    /**
     * Get the matches.
     *
     * @return array
     */
    public function getMatches(): ?array;

    /**
     * Set the matches.
     *
     * @param array $matches The matches
     *
     * @return static
     */
    public function setMatches(array $matches = null): self;

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments(): ?array;

    /**
     * Set the arguments.
     *
     * @param array $arguments The arguments
     *
     * @return static
     */
    public function setArguments(array $arguments = null): self;

    /**
     * Get the dependencies.
     *
     * @return array
     */
    public function getDependencies(): ?array;

    /**
     * Set the dependencies.
     *
     * @param array $dependencies The dependencies
     *
     * @return static
     */
    public function setDependencies(array $dependencies = null): self;
}
