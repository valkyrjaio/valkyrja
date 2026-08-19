<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Message\Contract;

use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;

interface QuestionContract extends MessageContract
{
    /**
     * Get the callable to call after the question is answered.
     *
     * @return callable(OutputContract, AnswerContract):OutputContract
     */
    public function getCallable(): callable;

    /**
     * @param callable(OutputContract, AnswerContract):OutputContract $callable The callable
     */
    public function withCallable(callable $callable): static;

    /**
     * Get the Answer message.
     */
    public function getAnswer(): AnswerContract;

    /**
     * Create a new Question with the specified Answer message.
     *
     * @param AnswerContract $answer The answer message
     */
    public function withAnswer(AnswerContract $answer): static;

    /**
     * Ask the question and get an updated Answer with the response.
     *
     * The Answer comes back unchanged when no response arrives. An empty line, a stdin stream that
     * does not open, and a read that fails all give this result, because each one leaves the
     * question unanswered. Read AnswerContract::hasBeenAnswered() to know whether the user answered.
     */
    public function ask(): AnswerContract;
}
