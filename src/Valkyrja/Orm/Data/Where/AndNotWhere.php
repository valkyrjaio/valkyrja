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

namespace Valkyrja\Orm\Data\Where;

use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\WhereType;

readonly class AndNotWhere extends Where
{
    public function __construct(
        Value $value,
        Comparison $comparison = Comparison::EQUALS,
    ) {
        parent::__construct(
            value: $value,
            comparison: $comparison,
            type: WhereType::AND_NOT,
        );
    }
}
