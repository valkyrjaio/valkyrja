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

namespace Valkyrja\Cli\Interaction\Factory;

use Override;
use Valkyrja\Cli\Interaction\Config;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory as Contract;
use Valkyrja\Cli\Interaction\Message\Contract\Message;
use Valkyrja\Cli\Interaction\Output\Contract\EmptyOutput;
use Valkyrja\Cli\Interaction\Output\Contract\FileOutput;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Interaction\Output\Contract\PlainOutput;
use Valkyrja\Cli\Interaction\Output\Contract\StreamOutput;

/**
 * Class OutputFactory.
 *
 * @author Melech Mizrachi
 */
class OutputFactory implements Contract
{
    public function __construct(
        protected Config $config = new Config()
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): Output {
        return new \Valkyrja\Cli\Interaction\Output\Output(
            $this->config->isInteractive,
            $this->config->isQuiet,
            $this->config->isSilent,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createEmptyOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): EmptyOutput {
        return new \Valkyrja\Cli\Interaction\Output\EmptyOutput(
            $this->config->isInteractive,
            $this->config->isQuiet,
            $this->config->isSilent,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createPlainOutput(
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): PlainOutput {
        return new \Valkyrja\Cli\Interaction\Output\PlainOutput(
            $this->config->isInteractive,
            $this->config->isQuiet,
            $this->config->isSilent,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createFileOutput(
        string $filepath,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): FileOutput {
        return new \Valkyrja\Cli\Interaction\Output\FileOutput(
            $filepath,
            $this->config->isInteractive,
            $this->config->isQuiet,
            $this->config->isSilent,
            $exitCode,
            ...$messages
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createStreamOutput(
        $stream,
        ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages
    ): StreamOutput {
        return new \Valkyrja\Cli\Interaction\Output\StreamOutput(
            $stream,
            $this->config->isInteractive,
            $this->config->isQuiet,
            $this->config->isSilent,
            $exitCode,
            ...$messages
        );
    }
}
