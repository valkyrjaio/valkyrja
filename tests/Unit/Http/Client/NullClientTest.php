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

namespace Valkyrja\Tests\Unit\Http\Client;

use Valkyrja\Http\Client\NullClient;
use Valkyrja\Http\Message\Request\Request;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the NullClient service.
 *
 * @author Melech Mizrachi
 */
class NullClientTest extends TestCase
{
    public function testSendRequest(): void
    {
        $client  = new NullClient();
        $request = new Request();

        $response = $client->sendRequest($request);

        self::assertInstanceOf(EmptyResponse::class, $response);
    }
}
