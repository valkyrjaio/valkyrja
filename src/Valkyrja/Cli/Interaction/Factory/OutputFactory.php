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
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\EmptyOutputContract;
use Valkyrja\Cli\Interaction\Output\Contract\FileOutputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Contract\PlainOutputContract;
use Valkyrja\Cli\Interaction\Output\Contract\StreamOutputContract;
use Valkyrja\Cli\Interaction\Output\EmptyOutput;
use Valkyrja\Cli\Interaction\Output\FileOutput;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Interaction\Output\PlainOutput;
use Valkyrja\Cli\Interaction\Output\StreamOutput;

class OutputFactory implements OutputFactoryContract
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
        MessageContract ...$messages
    ): OutputContract {
        return new Output(
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
        MessageContract ...$messages
    ): EmptyOutputContract {
        return new EmptyOutput(
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
        MessageContract ...$messages
    ): PlainOutputContract {
        return new PlainOutput(
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
        MessageContract ...$messages
    ): FileOutputContract {
        return new FileOutput(
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
        MessageContract ...$messages
    ): StreamOutputContract {
        return new StreamOutput(
            $stream,
            $this->config->isInteractive,
            $this->config->isQuiet,
            $this->config->isSilent,
            $exitCode,
            ...$messages
        );
    }
}
