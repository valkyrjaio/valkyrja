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
use Valkyrja\Cli\Interaction\Output\Contract\FileOutputContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionFileWriteException;

use function error_clear_last;
use function error_get_last;
use function file_put_contents;
use function strlen;

use const FILE_APPEND;

class FileOutput extends Output implements FileOutputContract
{
    /**
     * @param non-empty-string $filepath The filepath
     */
    public function __construct(
        protected string $filepath,
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
     */
    #[Override]
    public function getFilepath(): string
    {
        return $this->filepath;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withFilepath(string $filepath): static
    {
        $new = clone $this;

        $new->filepath = $filepath;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @throws CliInteractionFileWriteException When the write does not store the whole message
     */
    #[Override]
    protected function outputMessage(MessageContract $message): void
    {
        $data = $message->getFormattedText();

        error_clear_last();

        if ($this->filePutContents($this->filepath, $data) !== strlen($data)) {
            $reason = error_get_last()['message'] ?? 'no diagnostic available';

            throw new CliInteractionFileWriteException(
                "Unable to write the whole message to the file `$this->filepath`: $reason"
            );
        }
    }

    /**
     * Append data to a file.
     *
     * This call suppresses the diagnostic, because the return value reports the failure. An enabled
     * error handler turns the diagnostic into an ErrorException, which would replace the throwable.
     *
     * @param non-empty-string $filepath The filepath
     * @param string           $data     The data
     *
     * @return int<0, max>|false
     */
    protected function filePutContents(string $filepath, string $data): int|false
    {
        return @file_put_contents(filename: $filepath, data: $data, flags: FILE_APPEND);
    }
}
