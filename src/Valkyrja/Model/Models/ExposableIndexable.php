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

namespace Valkyrja\Model\Models;

/**
 * Trait ExposableIndexable.
 *
 * @author Melech Mizrachi
 */
trait ExposableIndexable
{
    use Exposable;
    use Indexable;

    /**
     * @inheritDoc
     */
    public function asExposedIndexedArray(string ...$properties): array
    {
        return static::getIndexedArrayFromMappedArray($this->asExposedArray(...$properties));
    }

    /**
     * @inheritDoc
     */
    public function asExposedChangedIndexedArray(): array
    {
        return static::getIndexedArrayFromMappedArray($this->asExposedChangedArray());
    }
}
