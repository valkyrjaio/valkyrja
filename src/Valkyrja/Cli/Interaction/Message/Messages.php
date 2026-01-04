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
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;

class Messages extends Message
{
    /** @var MessageContract[] */
    protected array $messages = [];

    public function __construct(
        MessageContract ...$messages
    ) {
        parent::__construct(' ');

        $this->messages = $messages;
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
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

        if ($text === '') {
            throw new InvalidArgumentException('No text found');
        }

        return $text;
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
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

        if ($text === '') {
            throw new InvalidArgumentException('No text found');
        }

        return $text;
    }
}
