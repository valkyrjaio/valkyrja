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

namespace Valkyrja\Tests\Classes\Cli\Interaction\Message;

use Override;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\Question;

/**
 * Testable Question class that manipulates the ask method to return an invalid answer the first time it is called, then subsequent answers are valid.
 */
final class QuestionAskManipulationClass extends Question
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
