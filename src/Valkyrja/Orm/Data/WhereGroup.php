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
use Valkyrja\Orm\Enum\WhereType;

use function implode;

readonly class WhereGroup implements Stringable
{
    /** @var WhereType */
    protected const WhereType TYPE = WhereType::DEFAULT;

    /** @var Where[] */
    public array $where;

    public function __construct(
        Where ...$where
    ) {
        $this->where = $where;
    }

    /**
     * Get the where group as a string.
     *
     * @return non-empty-string
     */
    #[Override]
    public function __toString(): string
    {
        $type = static::TYPE->value;

        return ($type === '' ? '' : "$type ")
            . '('
            . implode(' ', $this->where)
            . ')';
    }
}
