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

readonly class DatedMetadata
{
    /**
     * @param non-empty-string $format            The format for the created and modified date
     * @param non-empty-string $dateCreatedField  The date created field
     * @param non-empty-string $dateModifiedField The date modified field
     */
    public function __construct(
        public string $format = DateFormat::DEFAULT,
        public string $dateCreatedField = 'created_at',
        public string $dateModifiedField = 'updated_at',
    ) {
    }
}
