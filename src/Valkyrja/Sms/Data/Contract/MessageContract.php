<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Sms\Data\Contract;

interface MessageContract
{
    /**
     * Get the phone number to send to.
     */
    public function getTo(): string;

    /**
     * Set who to send to.
     *
     * @param string $to The to
     */
    public function withTo(string $to): static;

    /**
     * Get the phone number to set as sent from.
     */
    public function getFrom(): string;

    /**
     * Set the from.
     *
     * @param string $from The from
     */
    public function withFrom(string $from): static;

    /**
     * Get the text.
     */
    public function getText(): string;

    /**
     * Set the text.
     *
     * @param string $text The text
     */
    public function withText(string $text): static;

    /**
     * Get whether the text is unicode.
     */
    public function isUnicode(): bool;

    /**
     * Set whether the text is unicode.
     *
     * @param bool $isUnicode [optional] Whether the text is unicode
     */
    public function withIsUnicode(bool $isUnicode = true): static;
}
