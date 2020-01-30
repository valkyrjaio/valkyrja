<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Support\Collection;

/**
 * Class Query.
 *
 * @author Melech Mizrachi
 */
class Query extends Collection
{
    /**
     * Convert the query parameters in the collection to a query string.
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->count() <= 0) {
            return '';
        }

        return '?' . http_build_query($this->collection);
    }
}
