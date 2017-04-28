<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container;

use Closure;

/**
 * Class ServiceContext
 *
 * @package Valkyrja\Container
 *
 * @author  Melech Mizrachi
 */
class ServiceContext extends Service
{
    /**
     * The context class.
     *
     * @var string
     */
    protected $contextClass;

    /**
     * The context property.
     *
     * @var string
     */
    protected $contextProperty;

    /**
     * The context method.
     *
     * @var string
     */
    protected $contextMethod;

    /**
     * The context function.
     *
     * @var string
     */
    protected $contextFunction;

    /**
     * The context closure.
     *
     * @var \Closure
     */
    protected $contextClosure;

    /**
     * Get the context class.
     *
     * @return string
     */
    public function getContextClass():? string
    {
        return $this->contextClass;
    }

    /**
     * Set the context class.
     *
     * @param string $contextClass The context class
     *
     * @return $this
     */
    public function setContextClass(string $contextClass): self
    {
        $this->contextClass = $contextClass;

        return $this;
    }

    /**
     * Get the context property.
     *
     * @return string
     */
    public function getContextProperty():? string
    {
        return $this->contextProperty;
    }

    /**
     * Set the context property.
     *
     * @param string $contextProperty The context property
     *
     * @return $this
     */
    public function setContextProperty(string $contextProperty): self
    {
        $this->contextProperty = $contextProperty;

        return $this;
    }

    /**
     * Get the context method.
     *
     * @return string
     */
    public function getContextMethod():? string
    {
        return $this->contextMethod;
    }

    /**
     * Set the context method.
     *
     * @param string $contextMethod The context method
     *
     * @return $this
     */
    public function setContextMethod(string $contextMethod): self
    {
        $this->contextMethod = $contextMethod;

        return $this;
    }

    /**
     * Get the context function.
     *
     * @return string
     */
    public function getContextFunction():? string
    {
        return $this->contextFunction;
    }

    /**
     * Set the context function.
     *
     * @param string $contextFunction The context function
     *
     * @return $this
     */
    public function setContextFunction(string $contextFunction): self
    {
        $this->contextFunction = $contextFunction;

        return $this;
    }

    /**
     * Get the context closure.
     *
     * @return \Closure
     */
    public function getContextClosure():? Closure
    {
        return $this->contextClosure;
    }

    /**
     * Set the context closure.
     *
     * @param \Closure $contextClosure The context closure.
     *
     * @return $this
     */
    public function setContextClosure(Closure $contextClosure): self
    {
        $this->contextClosure = $contextClosure;

        return $this;
    }
}
