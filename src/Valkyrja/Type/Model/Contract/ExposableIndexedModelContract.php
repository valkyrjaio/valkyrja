<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Model\Contract;

interface ExposableIndexedModelContract extends IndexedModelContract
{
    /**
     * Get model as an indexed array with all properties including exposable ones.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array<int, mixed>
     */
    public function asExposedIndexedArray(string ...$properties): array;

    /**
     * Get model as an indexed array including only all changed properties including exposable ones.
     *
     * @return array<int, mixed>
     */
    public function asExposedChangedIndexedArray(): array;
}
