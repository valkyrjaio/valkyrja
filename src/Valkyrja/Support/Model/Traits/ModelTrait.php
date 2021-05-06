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
    protected array $__exposed = [];

    /**
     * The original properties.
     *
     * @var array
     */
    protected array $__originalProperties = [];

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

        $model->__setPropertiesInternal($properties, true);

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
        $this->__setPropertiesInternal($properties);
    }

    /**
     * Get a new model with new properties.
     *
     * @param array $properties The properties to modify
     *
     * @return static
     */
    public function __withProperties(array $properties): self
    {
        $model = clone $this;

        $model->__setPropertiesInternal($properties);

        return $model;
    }

    /**
     * Get model as an array.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    public function __toArray(string ...$properties): array
    {
        // Get the public properties
        $allProperties = array_merge(Obj::getProperties($this), $this->__exposed);

        if (! empty($properties)) {
            $allProperties = $this->__onlyProperties($allProperties, $properties);
        }

        // Ensure for each property we use the magic __get method so as to go through any magic get{Property} methods
        foreach ($allProperties as $property => $value) {
            $allProperties[$property] = $this->__get($property);
        }

        return $allProperties;
    }

    /**
     * Get an array of changed properties.
     *
     * @return array
     */
    public function __changed(): array
    {
        // The original properties set on the model
        $originalProperties = $this->__originalProperties;
        // The changed properties
        $changed = [];

        // Iterate through the model's properties
        foreach ($this->__asArrayForChangedComparison() as $property => $value) {
            $originalProperty = $originalProperties[$property] ?? null;

            // Determine if the property changed
            if ($originalProperty !== $value) {
                $changed[$property] = $value;
            }
        }

        return $changed;
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
     * @param string ...$properties The properties to expose
     *
     * @return void
     */
    public function __expose(string ...$properties): void
    {
        foreach ($properties as $property) {
            $this->__exposed[$property] = true;
        }
    }

    /**
     * Un-expose hidden fields or all fields.
     *
     * @param string ...$properties [optional] The properties to unexpose
     *
     * @return void
     */
    public function __unexpose(string ...$properties): void
    {
        if (empty($properties)) {
            $this->__exposed = [];

            return;
        }

        foreach ($properties as $property) {
            unset($this->__exposed[$property]);
        }
    }

    /**
     * The model as an array to compare with original properties to determine what changed.
     *
     * @return array
     */
    protected function __asArrayForChangedComparison(): array
    {
        return $this->__toArray();
    }

    /**
     * Get an array subset of properties to return from a given list out of the returnable properties.
     *
     * @param array $allProperties All the properties returnable
     * @param array $properties    The properties we wish to return
     *
     * @return array
     */
    protected function __onlyProperties(array $allProperties, array $properties): array
    {
        $onlyProperties = [];

        // Iterate through the list and set only those properties
        foreach ($properties as $onlyProperty) {
            if (isset($allProperties[$onlyProperty])) {
                $onlyProperties[$onlyProperty] = true;
            }
        }

        // Return the properties requested
        return $onlyProperties;
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties            The properties to set
     * @param bool  $setOriginalProperties [optional] Whether to set the original properties
     *
     * @return void
     */
    protected function __setPropertiesInternal(array $properties, bool $setOriginalProperties = false): void
    {
        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // Ensure the property exists before blindly setting
            if (property_exists($this, $property)) {
                if ($setOriginalProperties && ! isset($this->__originalProperties[$property])) {
                    $this->__originalProperties[$property] = $value;
                }

                // Set the property
                $this->__set($property, $value);
            }
        }
    }

}
