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
use Valkyrja\Cli\Interaction\Message\Contract\Message;
use Valkyrja\Cli\Interaction\Output\Contract\PlainOutput as Contract;

/**
 * Class PlainOutput.
 *
 * @author Melech Mizrachi
 */
class PlainOutput extends Output implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function outputMessage(Message $message): void
    {
        echo strip_tags($message->getFormattedText());
    }
}
