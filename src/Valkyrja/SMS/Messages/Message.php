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

namespace Valkyrja\SMS\Messages;

use Valkyrja\SMS\Message as Contract;

/**
 * Class Message.
 *
 * @author Melech Mizrachi
 */
class Message implements Contract
{
    /**
     * The message to.
     *
     * @var string
     */
    protected string $to;

    /**
     * The message from.
     *
     * @var string
     */
    protected string $from;

    /**
     * The message text.
     *
     * @var string
     */
    protected string $text;

    /**
     * Whether the text is unicode.
     *
     * @var bool
     */
    protected bool $isUnicode = true;

    /**
     * Get the phone number to send to.
     *
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * Set who to send to.
     *
     * @param string $to The to
     *
     * @return static
     */
    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get the phone number to set as sent from.
     *
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * Set the from.
     *
     * @param string $from The from
     *
     * @return static
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the text.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the text.
     *
     * @param string $text The text
     *
     * @return static
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get whether the text is unicode.
     *
     * @return bool
     */
    public function isUnicode(): bool
    {
        return $this->isUnicode;
    }

    /**
     * Set whether the text is unicode.
     *
     * @param bool $isUnicode [optional] Whether the text is unicode
     *
     * @return static
     */
    public function setIsUnicode(bool $isUnicode = true): self
    {
        $this->isUnicode = $isUnicode;

        return $this;
    }
}
