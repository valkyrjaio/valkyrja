<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Uri\Type;

use Override;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidPortException;
use Valkyrja\Type\Abstract\Type;

use function gettype;
use function is_int;
use function sprintf;

/**
 * @extends Type<int>
 */
class Port extends Type
{
    public function __construct(int $subject)
    {
        if ($subject >= 1 && $subject <= 65535) {
            $this->subject = $subject;

            return;
        }

        throw new HttpUriInvalidPortException('Invalid port argument passed.');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        if (! is_int($value)) {
            throw new HttpUriInvalidPortException(sprintf('Int expected value of type `%s` provided', gettype($value)));
        }

        return new static($value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): int
    {
        return $this->subject;
    }
}
