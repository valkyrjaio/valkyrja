<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Dispatcher;

use Closure;

use Valkyrja\Annotations\Annotatable;

/**
 * Class Dispatch
 *
 * @package Valkyrja\Dispatcher
 *
 * @author  Melech Mizrachi
 */
class Dispatch
{
    use Annotatable;

    /**
     * The id.
     *
     * @var string
     */
    protected $id;

    /**
     * The name.
     *
     * @var string
     */
    protected $name;

    /**
     * The closure.
     *
     * @var \Closure
     */
    protected $closure;

    /**
     * The dependencies.
     *
     * @var array
     */
    protected $dependencies;

    /**
     * Get the id.
     *
     * @return string
     */
    public function getId():? string
    {
        return $this->id;
    }

    /**
     * Set the id.
     *
     * @param string $id The id
     *
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName():? string
    {
        return $this->name;
    }

    /**
     * Set the name.
     *
     * @param string $name The name
     *
     * @return $this
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
    public function getClass():? string
    {
        return $this->class;
    }

    /**
     * Set the class.
     *
     * @param string $class The class
     *
     * @return $this
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
    public function getProperty():? string
    {
        return $this->property;
    }

    /**
     * Set the property.
     *
     * @param string $property The property
     *
     * @return $this
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
    public function getMethod():? string
    {
        return $this->method;
    }

    /**
     * Set the method.
     *
     * @param string $method The method
     *
     * @return $this
     */
    public function setMethod(string $method = null): self
    {
        $this->method = $method;

        return $this;
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
     * @return $this
     */
    public function setStaticMethod(string $staticMethod = null): self
    {
        $this->staticMethod = $staticMethod;

        return $this;
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
     * @return $this
     */
    public function setFunction(string $function = null): self
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get the closure.
     *
     * @return \Closure
     */
    public function getClosure():? Closure
    {
        return $this->closure;
    }

    /**
     * Set the closure.
     *
     * @param \Closure $closure The closure
     *
     * @return $this
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
    public function getMatches():? array
    {
        return $this->matches;
    }

    /**
     * Set the matches.
     *
     * @param array $matches The matches
     *
     * @return $this
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
    public function getArguments():? array
    {
        return $this->arguments;
    }

    /**
     * Set the arguments.
     *
     * @param array $arguments The arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments = null): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Get the dependencies.
     *
     * @return array
     */
    public function getDependencies():? array
    {
        return $this->dependencies;
    }

    /**
     * Set the dependencies.
     *
     * @param array $dependencies The dependencies
     *
     * @return $this
     */
    public function setDependencies(array $dependencies = null): self
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * Get an dispatch from properties.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Dispatcher\Dispatch
     */
    public static function getDispatch(array $properties): self
    {
        $dispatch = new Dispatch();

        $dispatch
            ->setClass($properties['class'] ?? null)
            ->setMethod($properties['method'] ?? null)
            ->setStaticMethod($properties['staticMethod'] ?? null)
            ->setProperty($properties['property'] ?? null)
            ->setFunction($properties['function'] ?? null)
            ->setClosure($properties['closure'] ?? null)
            ->setMatches($properties['matches'] ?? null)
            ->setArguments($properties['arguments'] ?? null)
            ->setDependencies($properties['dependencies'] ?? null);

        return $dispatch;
    }

    /**
     * Set the state of the dispatch.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Dispatcher\Dispatch
     */
    public static function __set_state(array $properties)
    {
        return static::getDispatch($properties);
    }
}
