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

namespace Valkyrja\Mail\Config;

use Valkyrja\Mail\Message\Contract\Message;
use Valkyrja\Support\Config as ParentConfig;

/**
 * Abstract Class MessageConfiguration.
 *
 * @author Melech Mizrachi
 */
abstract class MessageConfiguration extends ParentConfig
{
    /**
     * @param class-string<Message> $messageClass
     */
    public function __construct(
        public string $from,
        public string $name,
        public string $messageClass = \Valkyrja\Mail\Message\Message::class,
    ) {
    }
}
