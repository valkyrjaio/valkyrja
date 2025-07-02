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
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function getText(): string
    {
        return implode(
            '',
            array_map(
                static fn (Contract $message) => $message->getText(),
                $this->messages
            )
        );
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function getFormattedText(): string
    {
        return implode(
            '',
            array_map(
                static fn (Contract $message) => $message->getFormattedText(),
                $this->messages
            )
        );
    }
}
