<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Stream\Factory;

use Psr\Http\Message\StreamInterface;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream;

abstract class PsrStreamFactory
{
    /**
     * Get a Valkyrja Stream object from a PSR StreamInterface object.
     */
    public static function fromPsr(StreamInterface $stream): StreamContract
    {
        $stream->rewind();
        $contents = $stream->getContents();
        $stream->rewind();

        $valkyrjaStream = new Stream(PhpWrapper::temp);
        $valkyrjaStream->write($contents);
        $valkyrjaStream->rewind();

        return $valkyrjaStream;
    }
}
