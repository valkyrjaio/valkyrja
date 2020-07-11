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

namespace Valkyrja\SMS;

/**
 * Interface Message.
 *
 * @author Melech Mizrachi
 */
interface Message
{
    /**
     * Make a new message.
     *
     * @return static
     */
    public function make(): self;

    /**
     * Set who to send to.
     *
     * @param string $to The to
     *
     * @return static
     */
    public function setTo(string $to): self;

    /**
     * Set the from.
     *
     * @param string $from The from
     *
     * @return static
     */
    public function setFrom(string $from): self;

    /**
     * Set the text.
     *
     * @param string $text The text
     *
     * @return static
     */
    public function setText(string $text): self;

    /**
     * Set unicode text.
     *
     * @param string $unicodeText The unicode text
     *
     * @return static
     */
    public function setUnicodeText(string $unicodeText): self;

    /**
     * Send the mail.
     *
     * @return bool
     */
    public function send(): bool;
}
