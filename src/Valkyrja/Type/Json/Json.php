<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Json;

use JsonException;
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Array\Factory\ArrayFactory;
use Valkyrja\Type\Json\Contract\JsonContract;

use function is_string;

/**
 * @extends Type<array<string|int, mixed>>
 */
class Json extends Type implements JsonContract
{
    /**
     * @param array<string|int, mixed> $subject The json
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
            return new static(ArrayFactory::fromString($value));
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
        return ArrayFactory::toString($this->subject);
    }
}
