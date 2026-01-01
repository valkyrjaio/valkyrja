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

readonly class WhereGroup implements Stringable
{
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
        return '(' . implode(' ', $this->where) . ')';
    }
}
