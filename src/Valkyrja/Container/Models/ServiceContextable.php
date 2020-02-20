<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Models;

use Closure;

/**
 * Trait ServiceContextable.
 *
 * @author Melech Mizrachi
 */
trait ServiceContextable
{
    /**
     * The context class.
     *
     * @var string|null
     */
    protected ?string $contextClass = null;

    /**
     * The context property.
     *
     * @var string|null
     */
    protected ?string $contextProperty = null;

    /**
     * The context method.
     *
     * @var string|null
     */
    protected ?string $contextMethod = null;

    /**
     * The context function.
     *
     * @var string|null
     */
    protected ?string $contextFunction = null;

    /**
     * The context closure.
     *
     * @var Closure|null
     */
    protected ?Closure $contextClosure = null;

    /**
     * Get the context class.
     *
     * @return string
     */
    public function getContextClass(): ?string
    {
        return $this->contextClass;
    }

    /**
     * Set the context class.
     *
     * @param string $contextClass The context class
     *
     * @return static
     */
    public function setContextClass(string $contextClass): self
    {
        $this->contextClass = $contextClass;

        return $this;
    }

    /**
     * Get the context property.
     *
     * @return string|null
     */
    public function getContextProperty(): ?string
    {
        return $this->contextProperty;
    }

    /**
     * Set the context property.
     *
     * @param string $contextProperty The context property
     *
     * @return static
     */
    public function setContextProperty(string $contextProperty): self
    {
        $this->contextProperty = $contextProperty;

        return $this;
    }

    /**
     * Get the context method.
     *
     * @return string|null
     */
    public function getContextMethod(): ?string
    {
        return $this->contextMethod;
    }

    /**
     * Set the context method.
     *
     * @param string $contextMethod The context method
     *
     * @return static
     */
    public function setContextMethod(string $contextMethod): self
    {
        $this->contextMethod = $contextMethod;

        return $this;
    }

    /**
     * Get the context function.
     *
     * @return string|null
     */
    public function getContextFunction(): ?string
    {
        return $this->contextFunction;
    }

    /**
     * Set the context function.
     *
     * @param string $contextFunction The context function
     *
     * @return static
     */
    public function setContextFunction(string $contextFunction): self
    {
        $this->contextFunction = $contextFunction;

        return $this;
    }

    /**
     * Get the context closure.
     *
     * @return Closure|null
     */
    public function getContextClosure(): ?Closure
    {
        return $this->contextClosure;
    }

    /**
     * Set the context closure.
     *
     * @param Closure $contextClosure The context closure.
     *
     * @return static
     */
    public function setContextClosure(Closure $contextClosure): self
    {
        $this->contextClosure = $contextClosure;

        return $this;
    }
}
