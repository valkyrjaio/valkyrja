<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Interaction\Message;

use Override;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\Question;

/**
 * Testable Question class that manipulates the ask method to return an invalid answer the first time it is called, then subsequent answers are valid.
 */
final class QuestionAskManipulationFixture extends Question
{
    protected int $timesAsked = 0;

    #[Override]
    public function ask(): AnswerContract
    {
        if ($this->timesAsked > 0) {
            $this->timesAsked++;

            return $this->answer->withUserResponse($this->answer->getDefaultResponse());
        }

        $this->timesAsked++;

        return $this->answer->withUserResponse($this->answer->getDefaultResponse() . ' invalid');
    }

    public function getTimesAsked(): int
    {
        return $this->timesAsked;
    }
}
