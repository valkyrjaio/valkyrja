<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
