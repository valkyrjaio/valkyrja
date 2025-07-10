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

use Override;
use Stringable;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;

use function is_array;

/**
 * Class Value.
 *
 * @author Melech Mizrachi
 *
 * @psalm-type ValueType QueryBuilder|array<array-key, scalar|null>|scalar|null
 *
 * @phpstan-type ValueType QueryBuilder|array<array-key, scalar|null>|scalar|null
 */
readonly class Value implements Stringable
{
    /**
     * @param non-empty-string $name  The name of the value
     * @param ValueType        $value The value
     */
    public function __construct(
        public string $name,
        public QueryBuilder|array|string|float|int|bool|null $value = null,
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

        if ($value instanceof QueryBuilder) {
            return '(' . ((string) $value) . ')';
        }

        if (! is_array($value)) {
            return $nameBind;
        }

        return '(' . $nameBind . implode(", $nameBind", array_keys($value)) . ')';
    }
}
