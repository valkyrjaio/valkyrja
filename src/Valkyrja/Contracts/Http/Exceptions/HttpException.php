<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Http\Exceptions;

use Exception;

/**
 * Interface HttpException.
 *
 * @author Melech Mizrachi
 */
interface HttpException
{
    /**
     * Get the status code for this exception.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Get the headers set for this exception.
     *
     * @return array
     */
    public function getHeaders(): array;
}
