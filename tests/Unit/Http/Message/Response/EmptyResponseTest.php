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

namespace Valkyrja\Tests\Unit\Http\Message\Response;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Tests\Unit\TestCase;

class EmptyResponseTest extends TestCase
{
    public function testConstruct(): void
    {
        $response = new EmptyResponse();

        self::assertSame(StatusCode::NO_CONTENT, $response->getStatusCode());
        self::assertSame(StatusCode::NO_CONTENT->asPhrase(), $response->getReasonPhrase());
        self::assertEmpty($response->getBody()->getContents());
    }
}
