<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Model\Trait;

trait ExposableIndexable
{
    use Exposable;
    use Indexable;

    /**
     * @inheritDoc
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array<int, mixed>
     */
    public function asExposedIndexedArray(string ...$properties): array
    {
        return static::getIndexedArrayFromMappedArray($this->asExposedArray(...$properties));
    }

    /**
     * @inheritDoc
     *
     * @return array<int, mixed>
     */
    public function asExposedChangedIndexedArray(): array
    {
        return static::getIndexedArrayFromMappedArray($this->asExposedChangedArray());
    }
}
