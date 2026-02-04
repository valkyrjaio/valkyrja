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

namespace Valkyrja\Type\Model\Trait;

trait Exposable
{
    /**
     * The properties to expose.
     *
     * @var array<string, bool>
     */
    protected array $internalExposed = [];

    /**
     * @inheritDoc
     *
     * @return string[]
     */
    abstract public static function getExposable(): array;

    /**
     * @inheritDoc
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array<string, mixed>
     */
    public function asExposedArray(string ...$properties): array
    {
        return $this->internalArrayWithExposed([$this, 'asArray'], ...$properties);
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function asExposedChangedArray(): array
    {
        return $this->internalArrayWithExposed([$this, 'asChangedArray']);
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function asExposedOnlyArray(): array
    {
        return $this->internalArrayWithExposed([$this, 'asArray'], ...static::getExposable());
    }

    /**
     * @inheritDoc
     *
     * @param string ...$properties The properties to expose
     */
    public function expose(string ...$properties): void
    {
        foreach ($properties as $property) {
            $this->internalExposed[$property] = true;
        }
    }

    /**
     * @inheritDoc
     *
     * @param string ...$properties [optional] The properties to unexpose
     */
    public function unexpose(string ...$properties): void
    {
        if (empty($properties)) {
            $this->internalExposed = [];

            return;
        }

        foreach ($properties as $property) {
            unset($this->internalExposed[$property]);
        }
    }

    /**
     * Get all properties.
     *
     * @return array<string, mixed>
     */
    protected function internalGetAllProperties(): array
    {
        return array_merge(parent::internalGetAllProperties(), $this->internalExposed);
    }

    /**
     * Remove internal model properties from an array of properties.
     *
     * @param array<string, mixed> $properties The properties
     */
    protected function internalRemoveInternalProperties(array &$properties): void
    {
        parent::internalRemoveInternalProperties($properties);

        unset($properties['internalExposed']);
    }

    /**
     * Get an array with exposed properties.
     *
     * @param callable(string ...$properties): array<string, mixed> $callable      The callable
     * @param string                                                ...$properties The properties
     *
     * @return array<string, mixed>
     */
    protected function internalArrayWithExposed(callable $callable, string ...$properties): array
    {
        $exposable = static::getExposable();

        $this->expose(...$exposable);

        $array = $callable(...$properties);

        $this->unexpose(...$exposable);

        return $array;
    }
}
