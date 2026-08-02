<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Ulid;

use Override;
use Random\RandomException;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Ulid\Contract\UlidContract;
use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;
use Valkyrja\Type\Ulid\Throwable\Exception\UlidInvalidFromValueException;

use function gettype;
use function is_string;
use function sprintf;

/**
 * @extends Type<string>
 */
class Ulid extends Type implements UlidContract
{
    /**
     * @throws InvalidUlidException
     * @throws RandomException
     */
    public function __construct(string|null $subject = null)
    {
        if ($subject !== null) {
            UlidFactory::validate($subject);
        }

        $this->subject = $subject
            ?? UlidFactory::generate();
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
            throw new UlidInvalidFromValueException(sprintf('String or null expected value of type `%s` provided', gettype($value)));
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
