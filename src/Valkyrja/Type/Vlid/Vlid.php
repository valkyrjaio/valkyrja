<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Vlid;

use Override;
use Random\RandomException;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeRuntimeException;
use Valkyrja\Type\Vlid\Contract\VlidContract;
use Valkyrja\Type\Vlid\Factory\VlidFactory;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;
use Valkyrja\Type\Vlid\Throwable\Exception\VlidInvalidFromValueException;

use function gettype;
use function is_string;
use function sprintf;

/**
 * @extends Type<string>
 */
class Vlid extends Type implements VlidContract
{
    /**
     * @throws TypeInvalidArgumentException
     * @throws RandomException
     * @throws TypeRuntimeException
     */
    public function __construct(string|null $subject = null)
    {
        if ($subject !== null) {
            VlidFactory::validate($subject);
        }

        $this->subject = $subject
            ?? VlidV1Factory::generate();
    }

    /**
     * @inheritDoc
     *
     * @throws TypeInvalidArgumentException
     * @throws RandomException
     * @throws TypeRuntimeException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        if ($value !== null && ! is_string($value)) {
            throw new VlidInvalidFromValueException(sprintf('String or null expected value of type `%s` provided', gettype($value)));
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
