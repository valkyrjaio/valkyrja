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
    public function withTo(string $to): static
    {
        $new = clone $this;

        $new->to = $to;

        return $new;
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
    public function withFrom(string $from): static
    {
        $new = clone $this;

        $new->from = $from;

        return $new;
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
    public function withText(string $text): static
    {
        $new = clone $this;

        $new->text = $text;

        return $new;
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
    public function withIsUnicode(bool $isUnicode = true): static
    {
        $new = clone $this;

        $new->isUnicode = $isUnicode;

        return $new;
    }
}
