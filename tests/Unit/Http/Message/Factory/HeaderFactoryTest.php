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

namespace Valkyrja\Tests\Unit\Http\Message\Factory;

use Valkyrja\Http\Message\Factory\HeaderFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class HeaderFactoryTest extends TestCase
{
    public function testMarshalHeaders(): void
    {
        $server = [
            'REDIRECT_HTTP_TEST'        => 'REDIRECT_HTTP_TEST',
            'REDIRECT_HTTP_NO_OVERRIDE' => 'REDIRECT_HTTP_NO_OVERRIDE',
            'HTTP_NO_OVERRIDE'          => 'NO_OVERRIDE',
            'HTTP_SOMETHING'            => 'HTTP_SOMETHING',
            'HTTP_SOMETHING_ELSE'       => 'HTTP_SOMETHING_ELSE',
            'CONTENT_TYPE'              => 'CONTENT_TYPE',
            'BLAH'                      => 'BLAH',
        ];

        $headers = HeaderFactory::marshalHeaders($server);

        $expectedHeaders = [
            'test'           => ['REDIRECT_HTTP_TEST'],
            'no-override'    => ['NO_OVERRIDE'],
            'something'      => ['HTTP_SOMETHING'],
            'something-else' => ['HTTP_SOMETHING_ELSE'],
            'content-type'   => ['CONTENT_TYPE'],
        ];

        self::assertSame($expectedHeaders, $headers);
    }
}
