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

use Stringable;
use Valkyrja\Orm\Enum\SortOrder;

/**
 * Class OrderBy.
 *
 * @author Melech Mizrachi
 */
readonly class OrderBy implements Stringable
{
    /**
     * @param non-empty-string $field The field to order by
     */
    public function __construct(
        public string $field,
        public SortOrder $order = SortOrder::ASC,
    ) {
    }

    /**
     * Get the join clause as a string.
     *
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->field
            . ' '
            . $this->order->value;
    }
}
