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

namespace Valkyrja\Http\Message\Throwable\Exception;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;

class NotFoundHttpException extends HttpException
{
    /**
     * @param string|null $message [optional] The Exception message to throw
     */
    public function __construct(
        StatusCode|null $statusCode = null,
        string|null $message = null,
        HeaderCollectionContract|null $headers = null
    ) {
        parent::__construct($statusCode ?? StatusCode::NOT_FOUND, $message, $headers);
    }
}
