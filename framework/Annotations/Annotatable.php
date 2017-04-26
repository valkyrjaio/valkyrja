<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotations;

/**
 * Class Annotatable
 *
 * @package Valkyrja\Annotations
 *
 * @author  Melech Mizrachi
 */
trait Annotatable
{
    /**
     * The type.
     *
     * @var string
     */
    protected $type;

    /**
     * The class.
     *
     * @var string
     */
    protected $class;

    /**
     * The property.
     *
     * @var string
     */
    protected $property;

    /**
     * The method.
     *
     * @var string
     */
    protected $method;

    /**
     * The static method.
     *
     * @var string
     */
    protected $staticMethod;

    /**
     * The function.
     *
     * @var string
     */
    protected $function;

    /**
     * The matches.
     *
     * @var array
     */
    protected $matches;

    /**
     * The arguments.
     *
     * @var array
     */
    protected $arguments;

    /**
     * Get the type.
     *
     * @return string
     */
    public function getType():? string
    {
        return $this->type;
    }

    /**
     * Set the type.
     *
     * @param string $type The type
     *
     * @return void
     */
    public function setType(string $type = null)
    {
        $this->type = $type;
    }

    /**
     * Get the class.
     *
     * @return string
     */
    public function getClass():? string
    {
        return $this->class;
    }

    /**
     * Set the class.
     *
     * @param string $class The class
     *
     * @return void
     */
    public function setClass(string $class = null)
    {
        $this->class = $class;
    }

    /**
     * Get the property.
     *
     * @return string
     */
    public function getProperty():? string
    {
        return $this->property;
    }

    /**
     * Set the property.
     *
     * @param string $property The property
     *
     * @return void
     */
    public function setProperty(string $property = null)
    {
        $this->property = $property;
    }

    /**
     * Get the method.
     *
     * @return string
     */
    public function getMethod():? string
    {
        return $this->method;
    }

    /**
     * Set the method.
     *
     * @param string $method The method
     *
     * @return void
     */
    public function setMethod(string $method = null)
    {
        $this->method = $method;
    }

    /**
     * Get the static method.
     *
     * @return string
     */
    public function getStaticMethod():? string
    {
        return $this->staticMethod;
    }

    /**
     * Set the static method.
     *
     * @param string $staticMethod The static method
     *
     * @return void
     */
    public function setStaticMethod(string $staticMethod = null)
    {
        $this->staticMethod = $staticMethod;
    }

    /**
     * @return string
     */
    public function getFunction():? string
    {
        return $this->function;
    }

    /**
     * Set the function.
     *
     * @param string $function The function
     *
     * @return void
     */
    public function setFunction(string $function = null)
    {
        $this->function = $function;
    }

    /**
     * Get the matches.
     *
     * @return array
     */
    public function getMatches():? array
    {
        return $this->matches;
    }

    /**
     * Set the matches.
     *
     * @param array $matches The matches
     *
     * @return void
     */
    public function setMatches(array $matches = null)
    {
        $this->matches = $matches;
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments():? array
    {
        return $this->arguments;
    }

    /**
     * Set the arguments.
     *
     * @param array $arguments The arguments
     *
     * @return void
     */
    public function setArguments(array $arguments = null)
    {
        $this->arguments = $arguments;
    }
}
