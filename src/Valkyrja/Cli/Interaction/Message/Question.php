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

use Override;
use Valkyrja\Cli\Interaction\Formatter\Contract\FormatterContract;
use Valkyrja\Cli\Interaction\Formatter\QuestionFormatter;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\Contract\QuestionContract as Contract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;

use function fgets;
use function fopen;
use function is_callable;

/**
 * Class Question.
 */
class Question extends Message implements Contract
{
    /**
     * @param non-empty-string                                        $text     The text
     * @param callable(OutputContract, AnswerContract):OutputContract $callable The callable
     */
    public function __construct(
        string $text,
        protected $callable,
        protected AnswerContract $answer,
        FormatterContract|null $formatter = new QuestionFormatter()
    ) {
        if (! is_callable($this->callable)) {
            throw new InvalidArgumentException('$callable must be a valid callable');
        }

        parent::__construct($text, $formatter);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCallable(callable $callable): static
    {
        $new = clone $this;

        $new->callable = $callable;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAnswer(): AnswerContract
    {
        return $this->answer;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAnswer(AnswerContract $answer): static
    {
        $new = clone $this;

        $new->answer = $answer;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function ask(): AnswerContract
    {
        $answer = $this->answer;
        $handle = $this->fopen(filename: 'php://stdin', mode: 'rb');

        if ($handle === false) {
            // TODO: Determine if we want to throw RuntimeException (UnhandledStreamQuestionException) here
            return $answer;
        }

        $line = $this->fgets($handle);

        if ($line === false) {
            // TODO: Determine if we want to throw RuntimeException (UnhandledLineQuestionException) here
            return $answer;
        }

        $response = trim($line);

        if ($response === '') {
            return $answer;
        }

        return $answer->withUserResponse($response);
    }

    /**
     * @param non-empty-string $filename The filename to open
     * @param non-empty-string $mode     The mode
     *
     * @return resource|false
     */
    protected function fopen(string $filename, string $mode)
    {
        return fopen(filename: $filename, mode: $mode);
    }

    /**
     * @param resource $stream The stream
     */
    protected function fgets($stream): string|false
    {
        return fgets($stream);
    }
}
