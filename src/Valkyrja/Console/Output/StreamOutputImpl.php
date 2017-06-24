<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Output;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 */
class StreamOutputImpl extends OutputImpl implements StreamOutput
{
    /**
     * The stream.
     *
     * @var resource
     */
    protected $stream;

    /**
     * Output constructor.
     *
     * @param OutputFormatter $formatter The output formatter
     * @param resource        $stream    The resource to use as a stream
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct(OutputFormatter $formatter, $stream = null)
    {
        parent::__construct($formatter);

        // If there is no stream and the stdout failed
        if (! $stream = $stream ?? fopen('php://stdout', 'wb')) {
            throw new RuntimeException(
                'Unable to create stdout.'
            );
        }

        // If the stream isn't a valid resource or not a stream resource
        if (! is_resource($stream) || 'stream' !== get_resource_type($stream)) {
            throw new InvalidArgumentException(
                'Stream is not a valid stream resource.'
            );
        }

        // Set the stream
        $this->stream = $stream;
    }

    /**
     * Write a message out to the console.
     *
     * @param string $message
     * @param bool   $newLine
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function writeOut(string $message, bool $newLine): void
    {
        if (
            false === @fwrite($this->stream, $message)
            || ($newLine && (false === @fwrite($this->stream, PHP_EOL)))
        ) {
            // should never happen
            throw new RuntimeException(
                'Unable to write output.'
            );
        }

        fflush($this->stream);
    }
}
