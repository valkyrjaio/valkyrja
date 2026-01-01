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
use Valkyrja\Type\BuiltIn\Contract\ArrayContract as Contract;
use Valkyrja\Type\BuiltIn\Support\Arr as Helper;

use function is_string;

/**
 * Class ArrayT.
 *
 * @extends Type<array<array-key, mixed>>
 */
class ArrayT extends Type implements Contract
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
        if (is_string($value)) {
            return new static(Helper::fromString($value));
        }

        return new static((array) $value);
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
        return Helper::toString($this->subject);
    }
}
