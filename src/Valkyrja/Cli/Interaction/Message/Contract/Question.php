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

use Valkyrja\Cli\Interaction\Output\Contract\Output;

/**
 * Interface Question.
 *
 * @author Melech Mizrachi
 */
interface Question extends Message
{
    /**
     * Get the callable to call after the question is answered.
     *
     * @return callable(Output, Answer):Output
     */
    public function getCallable(): callable;

    /**
     * @param callable(Output, Answer):Output $callable The callable
     *
     * @return static
     */
    public function withCallable(callable $callable): static;

    /**
     * Get the Answer message.
     *
     * @return Answer
     */
    public function getAnswer(): Answer;

    /**
     * Create a new Question with the specified Answer message.
     *
     * @param Answer $answer The answer message
     *
     * @return static
     */
    public function withAnswer(Answer $answer): static;

    /**
     * Ask the question and get an updated Answer with the response.
     *
     * @return Answer
     */
    public function ask(): Answer;
}
