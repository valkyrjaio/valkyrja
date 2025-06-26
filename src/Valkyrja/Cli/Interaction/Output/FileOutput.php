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

namespace Valkyrja\Cli\Interaction\Output;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Contract\Message;
use Valkyrja\Cli\Interaction\Output\Contract\FileOutput as Contract;

/**
 * Class FileOutput.
 *
 * @author Melech Mizrachi
 */
class FileOutput extends Output implements Contract
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
        Message ...$messages
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
    public function getFilepath(): string
    {
        return $this->filepath;
    }

    /**
     * @inheritDoc
     */
    public function withFilepath(string $filepath): static
    {
        $new = clone $this;

        $new->filepath = $filepath;

        return $new;
    }

    /**
     * @inheritDoc
     */
    protected function outputMessage(Message $message): void
    {
        // TODO: Implement
    }
}
