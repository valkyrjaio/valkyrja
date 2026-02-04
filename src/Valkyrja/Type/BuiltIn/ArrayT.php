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

namespace Valkyrja\Type\BuiltIn;

use JsonException;
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\BuiltIn\Contract\ArrayContract;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * @extends Type<array<array-key, mixed>>
 */
class ArrayT extends Type implements ArrayContract
{
    /**
     * @param array<array-key, mixed> $subject The array
     */
    public function __construct(array $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static(Arr::fromMixed($value));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): array
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function asFlatValue(): string
    {
        return Arr::toString($this->subject);
    }
}
