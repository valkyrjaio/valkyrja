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

namespace Valkyrja\Type\Uuid;

use Override;
use Random\RandomException;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;
use Valkyrja\Type\Uuid\Contract\UuidV4Contract;
use Valkyrja\Type\Uuid\Factory\UuidV4Factory;

use function gettype;
use function is_string;
use function sprintf;

/**
 * @extends Type<string>
 */
class UuidV4 extends Type implements UuidV4Contract
{
    /**
     * @throws InvalidUlidException
     * @throws RandomException
     */
    public function __construct(string|null $subject = null)
    {
        if ($subject !== null) {
            UuidV4Factory::validate($subject);
        }

        $this->subject = $subject
            ?? UuidV4Factory::generate();
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
            throw new InvalidArgumentException(sprintf('String or null expected value of type `%s` provided', gettype($value)));
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
