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
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilderContract;

use function array_keys;
use function implode;
use function is_array;

/**
 * @psalm-type ValueType QueryBuilderContract|array<array-key, scalar|null>|scalar|null
 *
 * @phpstan-type ValueType QueryBuilderContract|array<array-key, scalar|null>|scalar|null
 */
readonly class Value implements Stringable
{
    /**
     * @param non-empty-string $name  The name of the value
     * @param ValueType        $value The value
     */
    public function __construct(
        public string $name,
        public QueryBuilderContract|array|string|float|int|bool|null $value = null,
    ) {
    }

    /**
     * Get the value as a string.
     *
     * @return non-empty-string
     */
    #[Override]
    public function __toString(): string
    {
        $nameBind = ":$this->name";
        $value    = $this->value;

        if ($value instanceof QueryBuilderContract) {
            return '(' . ((string) $value) . ')';
        }

        if (! is_array($value)) {
            return $nameBind;
        }

        return '(' . $nameBind . implode(", $nameBind", array_keys($value)) . ')';
    }
}
