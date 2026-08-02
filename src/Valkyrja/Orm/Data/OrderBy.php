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

use Override;
use Stringable;
use Valkyrja\Orm\Enum\SortOrder;

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
    #[Override]
    public function __toString(): string
    {
        return $this->field
            . ' '
            . $this->order->value;
    }
}
