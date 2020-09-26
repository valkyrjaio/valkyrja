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

namespace Valkyrja\Support\Model\Traits;

use JsonException;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Str;

use function array_keys;
use function get_object_vars;
use function method_exists;
use function property_exists;
use function ucwords;
use function Valkyrja\dd;

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

        $model->_setProperties($properties);

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
        $methodName = 'get' . ucwords(Str::toStudlyCase($name));

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
        $methodName = 'set' . ucwords(Str::toStudlyCase($name));

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
        $methodName = 'isset' . ucwords(Str::toStudlyCase($name));

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
    public function _getPropertyNames(): array
    {
        if (empty(static::$modelProperties)) {
            static::$modelProperties = array_keys($this->jsonSerialize());
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
    public function _setProperties(array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // Ensure the property exists before blindly setting
            if (property_exists($this, $property)) {
                // Set the property
                $this->{$property} = $value;
            }
        }
    }

    /**
     * Get model as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Get model as a deep array where all properties are also arrays.
     *
     * @throws JsonException
     *
     * @return array
     */
    public function toDeepArray(): array
    {
        /**
         * Why?!...
         *
         * Let me tell you a story. Sometimes models have embedded within them some relationships, and to properly
         *  ensure that we return a true array with all properties as arrays, and their properties we sort of
         *  need to do this.
         */
        return Arr::fromString($this->__toString());
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
     * @throws JsonException
     *
     * @return string
     */
    public function __toString(): string
    {
        return Arr::toString($this->toArray());
    }
}
