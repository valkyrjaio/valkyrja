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
use Valkyrja\Cli\Interaction\Message\Contract\Question;
use Valkyrja\Cli\Interaction\Output\Contract\Output as Contract;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 */
class Output implements Contract
{
    /**
     * The unwritten messages.
     *
     * @var Message[]
     */
    protected array $unwrittenMessages = [];

    /**
     * The written messages.
     *
     * @var Message[]
     */
    protected array $writtenMessages = [];

    public function __construct(
        protected bool $isInteractive = true,
        protected bool $isQuiet = false,
        protected ExitCode|int $exitCode = ExitCode::SUCCESS,
        Message ...$messages,
    ) {
        $this->unwrittenMessages = $messages;
    }

    /**
     * @inheritDoc
     *
     * @return Message[]
     */
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
     * @return Message[]
     */
    public function getWrittenMessages(): array
    {
        return $this->writtenMessages;
    }

    /**
     * @inheritDoc
     */
    public function hasWrittenMessage(): bool
    {
        return $this->writtenMessages !== [];
    }

    /**
     * @inheritDoc
     *
     * @return Message[]
     */
    public function getUnwrittenMessages(): array
    {
        return $this->unwrittenMessages;
    }

    /**
     * @inheritDoc
     */
    public function hasUnwrittenMessage(): bool
    {
        return $this->unwrittenMessages !== [];
    }

    /**
     * @inheritDoc
     */
    public function withMessages(Message ...$messages): static
    {
        $new = clone $this;

        $new->unwrittenMessages = $messages;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedMessages(Message ...$messages): static
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
    public function withAddedMessage(Message $message): static
    {
        $new = clone $this;

        $new->unwrittenMessages[] = $message;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function writeMessages(): static
    {
        $new = clone $this;

        foreach ($this->unwrittenMessages as $key => $message) {
            // If this output isn't quiet
            if (! $this->isQuiet) {
                // Write the message
                $new->writeMessage($message);
            }

            // Add the message to the written messages array
            $new->writtenMessages[] = $message;

            // Remove the message from the unwritten message array
            unset($new->unwrittenMessages[$key]);

            // If this is a question
            if ($message instanceof Question) {
                // Ask the question
                $new->askQuestion($message);
            }
        }

        // Ensure all unwritten messages are truly removed
        $this->unwrittenMessages = [];

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isInteractive(): bool
    {
        return $this->isInteractive;
    }

    /**
     * @inheritDoc
     */
    public function withIsInteractive(bool $isInteractive): static
    {
        $new = clone $this;

        $new->isInteractive = $isInteractive;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isQuiet(): bool
    {
        return $this->isQuiet;
    }

    /**
     * @inheritDoc
     */
    public function withIsQuiet(bool $isQuiet): static
    {
        $new = clone $this;

        $new->isQuiet = $isQuiet;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getExitCode(): ExitCode|int
    {
        return $this->exitCode;
    }

    /**
     * @inheritDoc
     */
    public function withExitCode(ExitCode|int $exitCode): static
    {
        $new = clone $this;

        $new->exitCode = $exitCode;

        return $new;
    }

    /**
     * Write a message.
     *
     * @param Message $message The message
     *
     * @return void
     */
    protected function writeMessage(Message $message): void
    {
        echo $message->getFormattedText();
    }

    /**
     * Ask a question message.
     *
     * @param Question $question The question
     *
     * @return static
     */
    protected function askQuestion(Question $question): static
    {
        $answer = $question->getAnswer();

        if ($this->isInteractive) {
            $answer = $question->ask();
        }

        $this->writeMessage($answer);

        $this->writtenMessages[] = $question;
        $this->writtenMessages[] = $answer;

        $callable = $question->getCallable();

        /** @var static $output */
        $output = $callable($this, $answer);

        return $output;
    }
}
