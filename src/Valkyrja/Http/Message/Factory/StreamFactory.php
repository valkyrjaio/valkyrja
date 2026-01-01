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

namespace Valkyrja\Http\Message\Factory;

use Psr\Http\Message\StreamInterface;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream as ValkyrjaStream;

/**
 * Abstract Class StreamFactory.
 */
abstract class StreamFactory
{
    public static function fromPsr(StreamInterface $stream): StreamContract
    {
        $stream->rewind();
        $contents = $stream->getContents();
        $stream->rewind();

        $valkyrjaStream = new ValkyrjaStream(PhpWrapper::temp);
        $valkyrjaStream->write($contents);
        $valkyrjaStream->rewind();

        return $valkyrjaStream;
    }
}
