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
 * Class StreamTellExceptionFixture.
 */
final class StreamTellExceptionFixture extends Stream
{
    #[Override]
    protected function tellStream($stream): int|false
    {
        return false;
    }
}
