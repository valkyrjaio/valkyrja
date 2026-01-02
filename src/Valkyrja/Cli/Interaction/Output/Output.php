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

use Override;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract as Contract;
use Valkyrja\Cli\Interaction\Writer\Contract\WriterContract;
use Valkyrja\Cli\Interaction\Writer\QuestionWriter;

class Output implements Contract
{
    /**
     * The unwritten messages.
     *
     * @var MessageContract[]
     */
    protected array $unwrittenMessages = [];

    /**
     * The written messages.
     *
     * @var MessageContract[]
     */
    protected array $writtenMessages = [];

    /**
     * The message writers.
     *
     * @var WriterContract[]
     */
    protected array $writers = [];

    public function __construct(
        protected bool $isInteractive = true,
        protected bool $isQuiet = false,
        protected bool $isSilent = false,
        protected ExitCode|int $exitCode = ExitCode::SUCCESS,
        MessageContract ...$messages,
    ) {
        $this->unwrittenMessages = $messages;

        $this->writers = [
            new QuestionWriter(),
        ];
    }

    /**
     * @inheritDoc
     *
     * @return MessageContract[]
     */
    #[Override]
    public function getMessages(): array
    {
        return [
            ...$this->writtenMessages,
            ...$this->unwrittenMessages,
        ];
    }

    /**
     * @inheritDoc
     *
     * @return MessageContract[]
     */
    #[Override]
    public function getWrittenMessages(): array
    {
        return $this->writtenMessages;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasWrittenMessage(): bool
    {
        return $this->writtenMessages !== [];
    }

    /**
     * @inheritDoc
     *
     * @return MessageContract[]
     */
    #[Override]
    public function getUnwrittenMessages(): array
    {
        return $this->unwrittenMessages;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasUnwrittenMessage(): bool
    {
        return $this->unwrittenMessages !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withMessages(MessageContract ...$messages): static
    {
        $new = clone $this;

        $new->unwrittenMessages = $messages;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedMessages(MessageContract ...$messages): static
    {
        $new = clone $this;

        $new->unwrittenMessages = [
            ...$new->unwrittenMessages,
            ...$messages,
        ];

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedMessage(MessageContract $message): static
    {
        $new = clone $this;

        $new->unwrittenMessages[] = $message;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function writeMessages(): static
    {
        $new = clone $this;

        // Avoid writing messages twice or more if writeMessages is called in a callback within the foreach loop
        $unwrittenMessages = $this->unwrittenMessages;
        // Ensure all unwritten messages are truly removed
        $new->unwrittenMessages = [];

        foreach ($unwrittenMessages as $message) {
            $new = $new->writeMessageViaWriter($message);
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getWriters(): array
    {
        return $this->writers;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withWriters(WriterContract ...$writers): static
    {
        $new = clone $this;

        $new->writers = $writers;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isInteractive(): bool
    {
        return $this->isInteractive;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withIsInteractive(bool $isInteractive): static
    {
        $new = clone $this;

        $new->isInteractive = $isInteractive;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isQuiet(): bool
    {
        return $this->isQuiet;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withIsQuiet(bool $isQuiet): static
    {
        $new = clone $this;

        $new->isQuiet = $isQuiet;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isSilent(): bool
    {
        return $this->isSilent;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withIsSilent(bool $isSilent): static
    {
        $new = clone $this;

        $new->isSilent = $isSilent;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getExitCode(): ExitCode|int
    {
        return $this->exitCode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withExitCode(ExitCode|int $exitCode): static
    {
        $new = clone $this;

        $new->exitCode = $exitCode;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function writeMessage(MessageContract $message): static
    {
        $this->setMessageAsWritten($message);

        if ($this->isSilent || ($this->isQuiet && $this->exitCode === ExitCode::SUCCESS)) {
            return $this;
        }

        $this->outputMessage($message);

        return $this;
    }

    /**
     * Write a message through a writer.
     *
     * @param MessageContract $message The message
     *
     * @return static
     */
    protected function writeMessageViaWriter(MessageContract $message): static
    {
        foreach ($this->writers as $writer) {
            if ($writer->shouldWriteMessage($message)) {
                return $writer->write($this, $message);
            }
        }

        return $this->writeMessage($message);
    }

    /**
     * Set a message as written.
     *
     * @param MessageContract $message The message
     *
     * @return void
     */
    protected function setMessageAsWritten(MessageContract $message): void
    {
        $this->writtenMessages[] = $message;
    }

    /**
     * Output a message.
     *
     * @param MessageContract $message The message
     *
     * @return void
     */
    protected function outputMessage(MessageContract $message): void
    {
        echo $message->getFormattedText();
    }
}
