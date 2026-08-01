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

namespace Valkyrja\Orm\Data;

readonly class EntityMetadata
{
    /**
     * @param DatedMetadata|null      $dated      [optional] The created and modified date metadata
     * @param SoftDeleteMetadata|null $softDelete [optional] The deleted date metadata
     */
    public function __construct(
        public DatedMetadata|null $dated = null,
        public SoftDeleteMetadata|null $softDelete = null,
    ) {
    }
}
