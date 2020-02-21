<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Outputs;

use InvalidArgumentException;
use function is_resource;
use const PHP_EOL;
use RuntimeException;
use Valkyrja\Console\OutputFormatter as OutputFormatterContract;
use Valkyrja\Console\StreamOutput as StreamOutputContract;

/**
 * Class StreamOutput.
 *
 * @author Melech Mizrachi
 */
class StreamOutput extends Output implements StreamOutputContract
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
     * @param OutputFormatterContract $formatter The output formatter
     * @param resource                $stream    The resource to use as a stream
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct(OutputFormatterContract $formatter, $stream = null)
    {
        parent::__construct($formatter);

        // Set the resource
        $resource = $stream ?? fopen('php://stdout', 'wb');

        // If the resource isn't a valid resource or not a stream
        if (! is_resource($resource) || 'stream' !== get_resource_type($resource)) {
            throw new InvalidArgumentException('Stream is not a valid stream resource.');
        }

        $this->stream = $resource;
    }

    /**
     * Write a message out to the console.
     *
     * @param string $message
     * @param bool   $newLine
     *
     * @throws RuntimeException
     *
     * @return void
     */
    protected function writeOut(string $message, bool $newLine): void
    {
        if (false === @fwrite($this->stream, $message) || ($newLine && (false === @fwrite($this->stream, PHP_EOL)))) {
            // should never happen
            throw new RuntimeException('Unable to write output.');
        }

        fflush($this->stream);
    }
}
