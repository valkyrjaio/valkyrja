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
