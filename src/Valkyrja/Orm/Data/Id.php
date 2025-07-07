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

/**
 * Class Id.
 *
 * @author Melech Mizrachi
 */
readonly class Id extends Value
{
    /**
     * @param non-empty-string|int $value The id value
     * @param non-empty-string     $name  The id name
     */
    public function __construct(
        string|int $value,
        string $name = 'id',
    ) {
        parent::__construct($name, $value);
    }
}
