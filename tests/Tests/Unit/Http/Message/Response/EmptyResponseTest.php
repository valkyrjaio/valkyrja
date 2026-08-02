<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Response;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class EmptyResponseTest extends TestCase
{
    public function testConstruct(): void
    {
        $response = new EmptyResponse();

        self::assertSame(StatusCode::NO_CONTENT, $response->getStatusCode());
        self::assertSame(StatusCode::NO_CONTENT->asPhrase(), $response->getReasonPhrase());
        self::assertEmpty($response->getBody()->getContents());
    }
}
