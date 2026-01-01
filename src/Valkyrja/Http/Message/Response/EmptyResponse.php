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
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\EmptyResponseContract as Contract;
use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;

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
     * @param array<string, string[]> $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStreamException
     */
    public function __construct(array $headers = self::DEFAULT_HEADERS)
    {
        parent::__construct(
            body: new Stream(mode: Mode::READ),
            statusCode: StatusCode::NO_CONTENT,
            headers: $headers
        );
    }
}
