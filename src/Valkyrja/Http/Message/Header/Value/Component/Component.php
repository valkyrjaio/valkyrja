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

namespace Valkyrja\Http\Message\Header\Value\Component;

use Override;
use Valkyrja\Http\Message\Header\Value\Component\Contract\ComponentContract as Contract;

use function explode;

/**
 * @phpstan-consistent-constructor
 *  Will be overridden if need be
 */
class Component implements Contract
{
    /**
     * Deliminator to use for token and text.
     *
     * @var non-empty-string
     */
    protected const string DELIMINATOR = '=';

    public function __construct(
        protected string $token,
        protected string|null $text = null
    ) {
    }

    public static function fromValue(string $value): static
    {
        $token = $value;
        $text  = null;

        if (str_contains($value, static::DELIMINATOR)) {
            [$token, $text] = explode(static::DELIMINATOR, $value);
        }

        return new static(token: $token, text: $text);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withToken(string $token): static
    {
        $new = clone $this;

        $new->token = $token;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getText(): string|null
    {
        return $this->text ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withText(string|null $text = null): static
    {
        $new = clone $this;

        $new->text = $text;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->token !== '' && $this->text !== null && $this->text !== ''
            ? "$this->token=$this->text"
            : $this->token;
    }
}
