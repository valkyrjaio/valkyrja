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

namespace Valkyrja\Model;

use function array_keys;
use function get_object_vars;
use function json_decode;
use function json_encode;
use function method_exists;
use function property_exists;
use function str_replace;
use function ucwords;

use const JSON_THROW_ON_ERROR;

/**
 * Trait ModelTrait.
 *
 * @author Melech Mizrachi
 */
trait ModelTrait
{
    /**
     * Array of properties in the model.
     *
     * @var array
     */
    protected static array $modelProperties = [];

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return static
     */
    public static function fromArray(array $properties): self
    {
        $model = new static();

        $model->setModelProperties($properties);

        return $model;
    }

    /**
     * Get a property.
     *
     * @param string $name The property to get
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        $methodName = str_replace('_', '', ucwords($name, '_'));
        $methodName = 'get' . $methodName;

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return $this->{$name};
    }

    /**
     * Set a property.
     *
     * @param string $name  The property to set
     * @param mixed  $value The value to set
     *
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $methodName = str_replace('_', '', ucwords($name, '_'));
        $methodName = 'set' . $methodName;

        if (method_exists($this, $methodName)) {
            $this->$methodName($value);

            return;
        }

        $this->{$name} = $value;
    }

    /**
     * Check if a property is set.
     *
     * @param string $name The property to check
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        $methodName = str_replace('_', '', ucwords($name, '_'));
        $methodName = 'isset' . $methodName;

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return property_exists($this, $name);
    }

    /**
     * Get the properties.
     *
     * @return string[]
     */
    public function getModelProperties(): array
    {
        if (empty(static::$modelProperties)) {
            static::$modelProperties = array_keys(get_object_vars($this));
        }

        return static::$modelProperties;
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return void
     */
    public function setModelProperties(array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // Set the property
            $this->{$property} = $value;
        }
    }

    /**
     * Get model as an array.
     *
     * @return array
     */
    public function asArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Get model as a deep array where all properties are also arrays.
     *
     * @return array
     */
    public function asDeepArray(): array
    {
        /**
         * Why?!...
         *
         * Let me tell you a story. Sometimes models have embedded within them some relationships, and to properly
         *  ensure that we return a true array with all properties as arrays, and their properties we sort of
         *  need to do this.
         */
        return json_decode($this->__toString(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Serialize properties for json_encode.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) json_encode($this->asArray(), JSON_THROW_ON_ERROR);
    }
}
