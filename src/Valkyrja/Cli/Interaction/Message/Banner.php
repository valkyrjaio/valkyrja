<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Message;

use Override;

use function str_repeat;
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
