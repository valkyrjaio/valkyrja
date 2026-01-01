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

namespace Valkyrja\Type\Model\Contract;

/**
 * Interface ExposableIndexedModelContract.
 */
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
