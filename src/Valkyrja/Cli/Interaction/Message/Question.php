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

namespace Valkyrja\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Exception\InvalidArgumentException;
use Valkyrja\Cli\Interaction\Formatter\Contract\Formatter;
use Valkyrja\Cli\Interaction\Message\Contract\Answer;
use Valkyrja\Cli\Interaction\Message\Contract\Question as Contract;
use Valkyrja\Cli\Interaction\Output\Contract\Output;

/**
 * Class Question.
 *
 * @author Melech Mizrachi
 */
class Question extends Message implements Contract
{
    /**
     * @param non-empty-string                $text     The text
     * @param callable(Output, Answer):Output $callable The callable
     */
    public function __construct(
        string $text,
        protected $callable,
        protected Answer $answer,
        Formatter|null $formatter = null
    ) {
        if (! is_callable($this->callable)) {
            throw new InvalidArgumentException('$callable must be a valid callable');
        }

        parent::__construct($text, $formatter);
    }

    /**
     * @inheritDoc
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @inheritDoc
     */
    public function withCallable(callable $callable): static
    {
        $new = clone $this;

        $new->callable = $callable;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    /**
     * @inheritDoc
     */
    public function withAnswer(Answer $answer): static
    {
        $new = clone $this;

        $new->answer = $answer;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function ask(): Answer
    {
        $answer = $this->answer;

        $handle = fopen('php://stdin', 'rb');

        if ($handle === false) {
            // TODO: Determine if we want to throw RuntimeException (UnhandledStreamQuestionException) here
            return $answer;
        }

        $line = fgets($handle);

        if ($line === false) {
            // TODO: Determine if we want to throw RuntimeException (UnhandledLineQuestionException) here
            return $answer;
        }

        $response = trim($line);

        if ($response === '') {
            // TODO: Determine if we want to throw InvalidArgumentException (InvalidQuestionAnswerException) here
            return $answer;
        }

        return $answer->withUserResponse($response);
    }
}
