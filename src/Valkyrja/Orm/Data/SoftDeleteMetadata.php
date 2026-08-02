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
