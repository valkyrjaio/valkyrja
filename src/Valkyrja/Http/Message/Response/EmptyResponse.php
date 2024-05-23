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

namespace Valkyrja\Http\Message\Response;

use InvalidArgumentException;
use Valkyrja\Http\Message\Constant\StatusCode;
use Valkyrja\Http\Message\Exception\InvalidStatusCode;
use Valkyrja\Http\Message\Exception\InvalidStream;
use Valkyrja\Http\Message\Response\Contract\EmptyResponse as Contract;

/**
 * Class EmptyResponse.
 *
 * @author Melech Mizrachi
 */
class EmptyResponse extends Response implements Contract
{
    /**
     * NativeEmptyResponse constructor.
     *
     * @param array $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(array $headers = self::DEFAULT_HEADERS)
    {
        parent::__construct(
            statusCode: StatusCode::NO_CONTENT,
            headers   : $headers
        );
    }
}
