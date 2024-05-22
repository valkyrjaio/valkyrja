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

namespace Valkyrja\Sms\Message\Contract;

use Valkyrja\Manager\Message\Contract\Message as Contract;

/**
 * Interface Message.
 *
 * @author Melech Mizrachi
 */
interface Message extends Contract
{
    /**
     * Get the phone number to send to.
     *
     * @return string
     */
    public function getTo(): string;

    /**
     * Set who to send to.
     *
     * @param string $to The to
     *
     * @return static
     */
    public function setTo(string $to): static;

    /**
     * Get the phone number to set as sent from.
     *
     * @return string
     */
    public function getFrom(): string;

    /**
     * Set the from.
     *
     * @param string $from The from
     *
     * @return static
     */
    public function setFrom(string $from): static;

    /**
     * Get the text.
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Set the text.
     *
     * @param string $text The text
     *
     * @return static
     */
    public function setText(string $text): static;

    /**
     * Get whether the text is unicode.
     *
     * @return bool
     */
    public function isUnicode(): bool;

    /**
     * Set whether the text is unicode.
     *
     * @param bool $isUnicode [optional] Whether the text is unicode
     *
     * @return static
     */
    public function setIsUnicode(bool $isUnicode = true): static;
}
