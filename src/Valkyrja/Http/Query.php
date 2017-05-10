<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Request.php
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Query as QueryContract;
use Valkyrja\Support\Collection;

/**
 * Class Query.
 *
 *
 * @author  Melech Mizrachi
 */
class Query extends Collection implements QueryContract
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

        $str      = '?';
        $lastItem = end($this->collection);

        foreach ($this->collection as $itemKey => $item) {
            $str .= $itemKey . '=' . $item;

            if ($item !== $lastItem) {
                $str .= '&';
            }
        }

        return $str;
    }
}
