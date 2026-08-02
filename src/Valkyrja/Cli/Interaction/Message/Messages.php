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
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;

use function array_map;
use function implode;

class Messages extends Message
{
    /** @var MessageContract[] */
    protected array $messages = [];

    public function __construct(
        MessageContract ...$messages
    ) {
        parent::__construct('');

        $this->messages = $messages;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getText(): string
    {
        $text = implode(
            '',
            array_map(
                static fn (MessageContract $message) => $message->getText(),
                $this->messages
            )
        );

        return $text;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFormattedText(): string
    {
        $text = implode(
            '',
            array_map(
                static fn (MessageContract $message) => $message->getFormattedText(),
                $this->messages
            )
        );

        return $text;
    }
}
