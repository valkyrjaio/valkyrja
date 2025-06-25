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

use Valkyrja\Cli\Interaction\Formatter\Contract\Formatter;

/**
 * Class NewLine.
 *
 * @author Melech Mizrachi
 */
class NewLine extends Message
{
    /**
     * @param non-empty-string $text The text
     */
    public function __construct(
        string $text = "\n",
        Formatter|null $formatter = null
    ) {
        parent::__construct($text, $formatter);
    }
}
