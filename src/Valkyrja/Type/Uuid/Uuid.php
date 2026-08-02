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
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;
use Valkyrja\Type\Uuid\Contract\UuidContract;
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Throwable\Exception\UuidInvalidFromValueException;

use function gettype;
use function is_string;
use function sprintf;

/**
 * @extends Type<string>
 */
class Uuid extends Type implements UuidContract
{
    /**
     * @throws InvalidUlidException
     */
    public function __construct(string $subject)
    {
        UuidFactory::validate($subject);

        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidUlidException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        if (! is_string($value)) {
            throw new UuidInvalidFromValueException(sprintf('String expected value of type `%s` provided', gettype($value)));
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
