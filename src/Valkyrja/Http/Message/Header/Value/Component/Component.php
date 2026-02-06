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
use Valkyrja\Http\Message\Header\Factory\HeaderFactory;
use Valkyrja\Http\Message\Header\Value\Component\Contract\ComponentContract;

use function explode;

/**
 * @phpstan-consistent-constructor
 *  Will be overridden if need be
 */
class Component implements ComponentContract
{
    public function __construct(
        protected string $token,
        protected string|null $text = null
    ) {
        $this->token = $this->filterPart($token);
        $this->text  = $this->filterText($text);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(string $value): static
    {
        $token = $value;
        $text  = null;

        $deliminator = '=';

        if (str_contains($value, $deliminator)) {
            [$token, $text] = explode($deliminator, $value);
        }

        $text = $text !== null ? trim($text) : null;

        return new static(token: trim($token), text: $text);
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

        $new->token = $this->filterPart($token);

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

        $new->text = $this->filterText($text);

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

    /**
     * Filter text.
     */
    protected function filterText(string|null $text = null): string|null
    {
        if ($text === null) {
            return null;
        }

        return $this->filterPart($text);
    }

    /**
     * Filter a part of the component (token or text).
     */
    protected function filterPart(string $part): string
    {
        $part = HeaderFactory::filterValue($part);

        HeaderFactory::assertValidValue($part);

        return $part;
    }
}
