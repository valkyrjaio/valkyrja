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
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\PlainOutputContract;

class PlainOutput extends Output implements PlainOutputContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function outputMessage(MessageContract $message): void
    {
        echo strip_tags($message->getText());
    }
}
