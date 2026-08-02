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

use const SEEK_SET;

/**
 * Class StreamSeekExceptionFixture.
 */
final class StreamSeekExceptionFixture extends Stream
{
    #[Override]
    protected function seekStream($stream, int $offset, int $whence = SEEK_SET): int
    {
        return -1;
    }
}
