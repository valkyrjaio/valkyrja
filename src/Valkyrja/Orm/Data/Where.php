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
        return $this->type->value
            . ' '
            . $this->comparison->value
            . ' '
            . ((string) $this->value);
    }
}
