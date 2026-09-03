<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Output;

use Override;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\StreamOutputContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionStreamWriteException;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionUnwritableStreamException;

use function error_clear_last;
use function error_get_last;
use function fwrite;
use function is_resource;
use function stream_get_meta_data;
use function strlen;
use function strpbrk;
use function substr;

class StreamOutput extends Output implements StreamOutputContract
{
    /**
     * @param resource $stream The stream
     */
    public function __construct(
        protected $stream,
        bool $isInteractive = true,
        bool $isQuiet = false,
        bool $isSilent = false,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        MessageContract ...$messages
    ) {
        parent::__construct(
            $isInteractive,
            $isQuiet,
            $isSilent,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     *
     * @return resource
     */
    #[Override]
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @inheritDoc
     *
     * @param resource $stream The stream
     */
    #[Override]
    public function withStream($stream): static
    {
        $new = clone $this;

        $new->stream = $stream;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * A short write is not a failure. A non-blocking stream takes a large message over several
     * calls, so the loop offers the remainder while the stream takes part of it.
     *
     * @throws CliInteractionUnwritableStreamException When the stream is closed, or its mode has
     *                                                 no write intent
     * @throws CliInteractionStreamWriteException      When the stream stops taking the data
     */
    #[Override]
    protected function outputMessage(MessageContract $message): void
    {
        $this->verifyWritable();

        $data   = $message->getFormattedText();
        $length = strlen($data);
        $offset = 0;

        while ($offset < $length) {
            error_clear_last();

            $written = $this->fwrite($this->stream, substr($data, $offset));

            if ($written === false || $written === 0) {
                $default = $written === false
                    ? 'the write failed'
                    : 'the stream took no byte of the offer';
                $reason  = error_get_last()['message'] ?? $default;

                throw new CliInteractionStreamWriteException(
                    "Unable to write the whole message to the stream: $reason"
                );
            }

            $offset += $written;
        }
    }

    /**
     * Verify the stream takes a write.
     *
     * A closed stream and a stream opened in a read mode both take nothing and raise no
     * diagnostic, so the write reports no cause. This check names the condition instead.
     *
     * @throws CliInteractionUnwritableStreamException When the stream is closed, or its mode has
     *                                                 no write intent
     */
    protected function verifyWritable(): void
    {
        // Psalm reads the declared resource type and cannot see a closed one, which the runtime
        // reports as `resource (closed)` and is_resource answers false for.
        /** @psalm-suppress DocblockTypeContradiction */
        if (! is_resource($this->stream)) {
            throw new CliInteractionUnwritableStreamException('The stream is closed');
        }

        $mode = $this->getMode();

        // Every writable fopen mode carries one of these characters, and a read mode carries none.
        if (strpbrk($mode, 'waxc+') === false) {
            throw new CliInteractionUnwritableStreamException("The stream mode `$mode` takes no write");
        }
    }

    /**
     * Get the mode of the stream.
     */
    protected function getMode(): string
    {
        return stream_get_meta_data($this->stream)['mode'];
    }

    /**
     * Write data to a stream.
     *
     * This call suppresses the diagnostic, because the return value reports the failure. An enabled
     * error handler turns the diagnostic into an ErrorException, which would replace the throwable.
     *
     * @param resource $stream The stream
     * @param string   $data   The data
     *
     * @return int|false
     */
    protected function fwrite($stream, string $data): int|false
    {
        return @fwrite(stream: $stream, data: $data);
    }
}
