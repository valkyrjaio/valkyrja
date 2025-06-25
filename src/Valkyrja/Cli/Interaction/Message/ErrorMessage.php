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

use Valkyrja\Cli\Interaction\Formatter\ErrorFormatter;

/**
 * Class ErrorMessage.
 *
 * @author Melech Mizrachi
 */
class ErrorMessage extends Message
{
    /**
     * @param non-empty-string $text The text
     */
    public function __construct(string $text)
    {
        parent::__construct(
            text: $text,
            formatter: new ErrorFormatter()
        );
    }

    /**
     * @return Message[]
     */
    public function asBanner(): array
    {
        $text       = "    $this->text    ";
        $textLength = strlen($text);
        $spaces     = str_repeat(' ', $textLength);

        return [
            new NewLine(),
            new self($spaces),
            new NewLine(),
            new self($text),
            new NewLine(),
            new self($spaces),
            new NewLine(),
        ];
    }
}
