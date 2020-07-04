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

namespace Valkyrja\Http\Exceptions;

use Valkyrja\Http\Constants\StatusCode;

/**
 * Class NotFoundHttpException.
 *
 * @author Melech Mizrachi
 */
class NotFoundHttpException extends HttpException
{
    /**
     * NotFoundHttpException constructor.
     *
     * @param int|null    $statusCode [optional] The status code to use
     * @param string|null $message    [optional] The Exception message to throw
     * @param array|null  $headers    [optional] The headers to send
     */
    public function __construct(int $statusCode = null, string $message = null, array $headers = null)
    {
        parent::__construct($statusCode ?? StatusCode::NOT_FOUND, $message, $headers);
    }
}
