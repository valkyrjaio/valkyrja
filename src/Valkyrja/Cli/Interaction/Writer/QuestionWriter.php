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

namespace Valkyrja\Cli\Interaction\Writer;

use Override;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Contract\QuestionContract;
use Valkyrja\Cli\Interaction\Message\Message as MessageMessage;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Cli\Interaction\Writer\Contract\WriterContract;

use function array_map;
use function implode;

class QuestionWriter implements WriterContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function shouldWriteMessage(MessageContract $message): bool
    {
        return $message instanceof QuestionContract;
    }

    /**
     * @inheritDoc
     *
     * @template O of OutputContract
     *
     * @param O $output The output
     *
     * @return O
     */
    #[Override]
    public function write(OutputContract $output, MessageContract $message): OutputContract
    {
        if (! $message instanceof QuestionContract) {
            throw new InvalidArgumentException('This writer expects only questions');
        }

        return $this->askQuestion($output, $message);
    }

    /**
     * Ask a question.
     *
     * @template O of OutputContract
     *
     * @param O $output The output
     *
     * @return O
     */
    protected function askQuestion(OutputContract $output, QuestionContract $question): OutputContract
    {
        $output = $this->writeQuestion($output, $question);

        $answer = $question->getAnswer();

        if ($output->isInteractive() && ! $output->isQuiet() && ! $output->isSilent()) {
            $answer = $question->ask();

            if (! $answer->isValidResponse()) {
                // For posterity add the answer with the invalid user response to the written messages list
                $output = $this->writeAnswerAfterResponse($output, $answer);

                // Re-ask the question
                return $this->askQuestion($output, $question);
            }
        }

        $output = $this->writeAnswerAfterResponse($output, $answer);

        $callable = $question->getCallable();

        /** @var O $output */
        $output = $callable($output, $answer);

        return $output;
    }

    /**
     * Write a question.
     *
     * @template O of OutputContract
     *
     * @param O $output The output
     *
     * @return O
     */
    protected function writeQuestion(OutputContract $output, QuestionContract $question): OutputContract
    {
        // Write the question text
        $output = $output->writeMessage($question);

        $answer = $question->getAnswer();

        $validResponses = $answer->getAllowedResponses();

        if ($validResponses !== []) {
            // (`valid` or `also valid` or `another valid value`)
            $output = $output->writeMessage(new MessageMessage(' ('));
            $output = $output->writeMessage(new MessageMessage(implode(' or ', array_map(static fn (string $value) => "`$value`", $validResponses))));
            $output = $output->writeMessage(new MessageMessage(')'));
        }

        // [default: "defaultResponse"]
        $output = $output->writeMessage(new MessageMessage(' [default: "'));
        $output = $output->writeMessage(new MessageMessage($answer->getDefaultResponse(), new HighlightedTextFormatter()));
        $output = $output->writeMessage(new MessageMessage('"]'));

        // :
        // > response will be typed here
        $output = $output->writeMessage(new MessageMessage(':'));
        $output = $output->writeMessage(new NewLine());
        $output = $output->writeMessage(new MessageMessage('> '));

        return $output;
    }

    /**
     * Write an answer after it has been answered.
     *
     * @template O of OutputContract
     *
     * @param O $output The output
     *
     * @return O
     */
    protected function writeAnswerAfterResponse(OutputContract $output, AnswerContract $answer): OutputContract
    {
        $output = $output->writeMessage($answer);
        $output = $output->writeMessage(new NewLine());

        return $output;
    }
}
