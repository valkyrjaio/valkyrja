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
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\WhereType;

readonly class Where implements Stringable
{
    public function __construct(
        public Value $value,
        public Comparison $comparison = Comparison::EQUALS,
        public WhereType $type = WhereType::DEFAULT,
    ) {
    }

    /**
     * Get the where clause as a string.
     *
     * @return non-empty-string
     */
    #[Override]
    public function __toString(): string
    {
        $type = $this->type->value;

        return ($type === '' ? '' : "$type ")
            . $this->value->name
            . ' '
            . $this->comparison->value
            . ' '
            . ((string) $this->value);
    }
}
