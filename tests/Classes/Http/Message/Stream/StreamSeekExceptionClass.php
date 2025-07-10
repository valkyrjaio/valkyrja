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

namespace Valkyrja\Tests\Classes\Http\Message\Stream;

use Override;
use Valkyrja\Http\Message\Stream\Stream;

use const SEEK_SET;

/**
 * Class StreamSeekExceptionClass.
 *
 * @author Melech Mizrachi
 */
class StreamSeekExceptionClass extends Stream
{
    #[Override]
    protected function seekStream($stream, int $offset, int $whence = SEEK_SET): int
    {
        return -1;
    }
}
