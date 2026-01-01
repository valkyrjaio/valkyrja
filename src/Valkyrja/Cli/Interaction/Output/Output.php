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
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Contract\QuestionContract;
use Valkyrja\Cli\Interaction\Message\Message as MessageMessage;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract as Contract;

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
     * @var MessageContract[]
     */
    protected array $unwrittenMessages = [];

    /**
     * The written messages.
     *
     * @var MessageContract[]
     */
    protected array $writtenMessages = [];

    public function __construct(
        protected bool $isInteractive = true,
        protected bool $isQuiet = false,
        protected bool $isSilent = false,
        protected ExitCode|int $exitCode = ExitCode::SUCCESS,
        MessageContract ...$messages,
    ) {
        $this->unwrittenMessages = $messages;
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
            $new->determineQuestionType($message);
        }

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
     * Determine the type of message and write it.
     *
     * @param MessageContract $message The message
     *
     * @return void
     */
    protected function determineQuestionType(MessageContract $message): void
    {
        match (true) {
            $message instanceof QuestionContract => $this->askQuestion($message),
            default                              => $this->writeMessage($message),
        };
    }

    /**
     * Write a message.
     *
     * @param MessageContract $message The message
     *
     * @return void
     */
    protected function writeMessage(MessageContract $message): void
    {
        $this->setMessageAsWritten($message);

        if ($this->isSilent || ($this->isQuiet && $this->exitCode === ExitCode::SUCCESS)) {
            return;
        }

        $this->outputMessage($message);
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

    /**
     * Ask a question.
     *
     * @param QuestionContract $question The question
     *
     * @return static
     */
    protected function askQuestion(QuestionContract $question): static
    {
        $this->writeQuestion($question);

        $answer = $question->getAnswer();

        if ($this->isInteractive && ! $this->isQuiet && ! $this->isSilent) {
            $answer = $question->ask();

            if (! $answer->isValidResponse()) {
                // For posterity add the answer with the invalid user response to the written messages list
                $this->writeAnswerAfterResponse($answer);

                // Re-ask the question
                return $this->askQuestion($question);
            }
        }

        $this->writeAnswerAfterResponse($answer);

        $callable = $question->getCallable();

        /** @var static $output */
        $output = $callable($this, $answer);

        return $output;
    }

    /**
     * Write a question.
     *
     * @param QuestionContract $question The question
     *
     * @return void
     */
    protected function writeQuestion(QuestionContract $question): void
    {
        // Write the question text
        $this->writeMessage($question);

        $answer = $question->getAnswer();

        $validResponses = $answer->getAllowedResponses();

        if ($validResponses !== []) {
            // (`valid` or `also valid` or `another valid value`)
            $this->writeMessage(new MessageMessage(' ('));
            $this->writeMessage(new MessageMessage(implode(' or ', array_map(static fn (string $value) => "`$value`", $validResponses))));
            $this->writeMessage(new MessageMessage(')'));
        }

        // [default: "defaultResponse"]
        $this->writeMessage(new MessageMessage(' [default: "'));
        $this->writeMessage(new MessageMessage($answer->getDefaultResponse(), new HighlightedTextFormatter()));
        $this->writeMessage(new MessageMessage('"]'));

        // :
        // > response will be typed here
        $this->writeMessage(new MessageMessage(':'));
        $this->writeMessage(new NewLine());
        $this->writeMessage(new MessageMessage('> '));
    }

    /**
     * Write an answer after it has been answered.
     *
     * @param AnswerContract $answer The answer
     *
     * @return void
     */
    protected function writeAnswerAfterResponse(AnswerContract $answer): void
    {
        $this->setMessageAsWritten($answer);
        $this->writeMessage(new NewLine());
    }
}
