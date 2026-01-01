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

use Override;

use function strlen;

class Banner extends Message
{
    protected Messages $messages;

    public function __construct(
        protected Message $message
    ) {
        parent::__construct($message->getText());

        $text       = "    $this->text    ";
        $textLength = strlen($text);
        $spaces     = str_repeat(' ', $textLength);

        $this->messages = new Messages(
            new NewLine(),
            $this->message->withText($spaces),
            new NewLine(),
            $this->message->withText($text),
            new NewLine(),
            $this->message->withText($spaces),
            new NewLine(),
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getText(): string
    {
        return $this->messages->getText();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFormattedText(): string
    {
        return $this->messages->getFormattedText();
    }
}
