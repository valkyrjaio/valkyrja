<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Annotations;

/**
 * Interface Annotation
 *
 * @package Valkyrja\Contracts\Annotations
 *
 * @author  Melech Mizrachi
 */
interface Annotation
{
    /**
     * Get the type.
     *
     * @return string
     */
    public function getType():? string;

    /**
     * Set the type.
     *
     * @param string $type The type
     *
     * @return void
     */
    public function setType(string $type = null);

    /**
     * Get the class.
     *
     * @return string
     */
    public function getClass():? string;

    /**
     * Set the class.
     *
     * @param string $class The class
     *
     * @return mixed
     */
    public function setClass(string $class = null);

    /**
     * Get the property.
     *
     * @return string
     */
    public function getProperty():? string;

    /**
     * Set the property.
     *
     * @param string $property The property
     *
     * @return mixed
     */
    public function setProperty(string $property = null);

    /**
     * Get the method.
     *
     * @return string
     */
    public function getMethod():? string;

    /**
     * Set the method.
     *
     * @param string $method The method
     *
     * @return mixed
     */
    public function setMethod(string $method = null);

    /**
     * Get the static method.
     *
     * @return string
     */
    public function getStaticMethod():? string;

    /**
     * Set the static method.
     *
     * @param string $staticMethod The static method
     *
     * @return mixed
     */
    public function setStaticMethod(string $staticMethod = null);

    /**
     * Get the function.
     *
     * @return string
     */
    public function getFunction():? string;

    /**
     * Set the function.
     *
     * @param string $function The function
     *
     * @return mixed
     */
    public function setFunction(string $function = null);

    /**
     * Get the matches.
     *
     * @return array
     */
    public function getMatches():? array;

    /**
     * Set the matches.
     *
     * @param array $matches The matches
     *
     * @return void
     */
    public function setMatches(array $matches = null);

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments():? array;

    /**
     * Set the arguments.
     *
     * @param array $arguments The arguments
     *
     * @return void
     */
    public function setArguments(array $arguments = null);
}
