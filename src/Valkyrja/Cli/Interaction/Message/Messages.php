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
use Valkyrja\Cli\Interaction\Exception\InvalidArgumentException;
use Valkyrja\Cli\Interaction\Message\Contract\Message as Contract;

/**
 * Class Messages.
 *
 * @author Melech Mizrachi
 */
class Messages extends Message
{
    /** @var Contract[] */
    protected array $messages = [];

    public function __construct(
        Contract ...$messages
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
                static fn (Contract $message) => $message->getText(),
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
                static fn (Contract $message) => $message->getFormattedText(),
                $this->messages
            )
        );

        if ($text === '') {
            throw new InvalidArgumentException('No text found');
        }

        return $text;
    }
}
