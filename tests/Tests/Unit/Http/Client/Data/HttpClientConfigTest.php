<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Client\Data;

use Valkyrja\Http\Client\Data\Contract\HttpClientConfigContract;
use Valkyrja\Http\Client\Data\HttpClientConfig;
use Valkyrja\Http\Client\Manager\GuzzleClient;
use Valkyrja\Http\Client\Manager\NullClient;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HttpClientConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(HttpClientConfigContract::class, new HttpClientConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(GuzzleClient::class, new HttpClientConfig()->defaultClient);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(NullClient::class, new HttpClientConfig(defaultClient: NullClient::class)->defaultClient);
    }
}
