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

namespace Valkyrja\Sms\Data;

use Override;
use Valkyrja\Sms\Data\Contract\MessageContract;

class Message implements MessageContract
{
    public function __construct(
        protected string $to,
        protected string $from,
        protected string $text,
        protected bool $isUnicode = true
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setTo(string $to): static
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setFrom(string $from): static
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isUnicode(): bool
    {
        return $this->isUnicode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setIsUnicode(bool $isUnicode = true): static
    {
        $this->isUnicode = $isUnicode;

        return $this;
    }
}
