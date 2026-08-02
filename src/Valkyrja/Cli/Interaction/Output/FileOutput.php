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
     */
    #[Override]
    protected function outputMessage(MessageContract $message): void
    {
        // TODO: Implement
    }
}
