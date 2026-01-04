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
     */
    public function ask(): AnswerContract;
}
