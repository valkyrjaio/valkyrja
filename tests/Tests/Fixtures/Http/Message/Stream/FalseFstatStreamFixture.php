<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Message\Stream;

use Override;
use Valkyrja\Http\Message\Stream\Stream;

/**
 * Class FalseFstatStreamFixture.
 */
final class FalseFstatStreamFixture extends Stream
{
    #[Override]
    protected function getStreamStats($stream): array|false
    {
        return false;
    }
}
