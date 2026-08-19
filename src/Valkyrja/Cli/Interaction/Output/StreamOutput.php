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

use function fwrite;
use function strlen;

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
     * @throws CliInteractionStreamWriteException When the write does not store the whole message
     */
    #[Override]
    protected function outputMessage(MessageContract $message): void
    {
        $data = $message->getFormattedText();

        if ($this->fwrite($this->stream, $data) !== strlen($data)) {
            throw new CliInteractionStreamWriteException('Unable to write to the stream');
        }
    }

    /**
     * Write data to a stream.
     *
     * The diagnostic is suppressed because the return value reports the failure. An enabled error
     * handler turns the diagnostic into an ErrorException, which would replace the throwable.
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
