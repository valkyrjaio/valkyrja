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
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionUnwritableStreamException;

use function fwrite;

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
     * @throws CliInteractionUnwritableStreamException When the stream cannot be written to
     */
    #[Override]
    protected function outputMessage(MessageContract $message): void
    {
        if ($this->fwrite($this->stream, $message->getFormattedText()) === false) {
            throw new CliInteractionUnwritableStreamException('Unable to write to the stream');
        }
    }

    /**
     * Write data to a stream.
     *
     * @param resource $stream The stream
     * @param string   $data   The data
     *
     * @return int|false
     */
    protected function fwrite($stream, string $data): int|false
    {
        return fwrite(stream: $stream, data: $data);
    }
}
