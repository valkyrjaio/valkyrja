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

use Valkyrja\Orm\Constant\DateFormat;

readonly class SoftDeleteMetadata
{
    /**
     * @param non-empty-string $format           The format for the deleted date
     * @param non-empty-string $dateDeletedField The date deleted field
     */
    public function __construct(
        public string $format = DateFormat::DEFAULT,
        public string $dateDeletedField = 'deleted_at',
    ) {
    }
}
