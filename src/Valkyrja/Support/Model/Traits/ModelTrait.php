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
use Valkyrja\Support\Type\Obj;
use Valkyrja\Support\Type\Str;

use function method_exists;
use function property_exists;

/**
 * Trait ModelTrait.
 *
 * @author Melech Mizrachi
 */
trait ModelTrait
{
    /**
     * The properties to expose.
     *
     * @var string[]
     */
    protected static array $exposed = [];

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

        $model->__setProperties($properties);

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
        $methodName = 'get' . Str::toStudlyCase($name);

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
        $methodName = 'set' . Str::toStudlyCase($name);

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
        $methodName = 'isset' . Str::toStudlyCase($name);

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return property_exists($this, $name);
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return void
     */
    public function __setProperties(array $properties): void
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
    public function __toArray(): array
    {
        // Get the public properties
        $properties = Obj::getProperties($this);

        // Iterate through properties to expose
        foreach (static::$exposed as $exposedProperty => $value) {
            $properties[$exposedProperty] = $this->{$exposedProperty};
        }

        return $properties;
    }

    /**
     * Serialize properties for json_encode.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->__toArray();
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
        return Arr::toString($this->jsonSerialize());
    }

    /**
     * Expose hidden fields or all fields.
     *
     * @param string ...$properties The field(s) to expose
     *
     * @return void
     */
    public function __expose(string ...$properties): void
    {
        foreach ($properties as $property) {
            static::$exposed[$property] = true;
        }
    }

    /**
     * Un-expose hidden fields or all fields.
     *
     * @param string ...$properties The field(s) to expose
     *
     * @return void
     */
    public function __unexpose(string ...$properties): void
    {
        if (empty($properties)) {
            static::$exposed = [];

            return;
        }

        foreach ($properties as $property) {
            unset(static::$exposed[$property]);
        }
    }
}
