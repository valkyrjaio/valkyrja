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

use Valkyrja\Http\Message\Stream\Stream;

/**
 * Class StreamWriteExceptionClass.
 *
 * @author Melech Mizrachi
 */
class StreamWriteExceptionClass extends Stream
{
    protected function writeToStream($stream, string $data): int|false
    {
        return false;
    }
}
