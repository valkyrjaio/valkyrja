<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Annotations;

use Closure;

/**
 * Class ServiceContext.
 *
 * @author Melech Mizrachi
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
     * @return void
     */
    public function setContextClass(string $contextClass): void
    {
        $this->contextClass = $contextClass;
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
     * @return void
     */
    public function setContextProperty(string $contextProperty): void
    {
        $this->contextProperty = $contextProperty;
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
     * @return void
     */
    public function setContextMethod(string $contextMethod): void
    {
        $this->contextMethod = $contextMethod;
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
     * @return void
     */
    public function setContextFunction(string $contextFunction): void
    {
        $this->contextFunction = $contextFunction;
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
     * @return void
     */
    public function setContextClosure(Closure $contextClosure): void
    {
        $this->contextClosure = $contextClosure;
    }
}
