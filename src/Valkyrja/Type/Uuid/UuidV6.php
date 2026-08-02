<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Uuid;

use Override;
use Random\RandomException;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;
use Valkyrja\Type\Uuid\Contract\UuidV6Contract;
use Valkyrja\Type\Uuid\Factory\UuidV6Factory;
use Valkyrja\Type\Uuid\Throwable\Exception\UuidInvalidFromValueException;

use function gettype;
use function is_string;
use function sprintf;

/**
 * @extends Type<string>
 */
class UuidV6 extends Type implements UuidV6Contract
{
    /**
     * @throws InvalidUlidException
     * @throws RandomException
     */
    public function __construct(string|null $subject = null)
    {
        if ($subject !== null) {
            UuidV6Factory::validate($subject);
        }

        $this->subject = $subject
            ?? UuidV6Factory::generate();
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidUlidException
     * @throws RandomException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        if ($value !== null && ! is_string($value)) {
            throw new UuidInvalidFromValueException(sprintf('String or null expected value of type `%s` provided', gettype($value)));
        }

        return new static($value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string
    {
        return $this->asValue();
    }
}
