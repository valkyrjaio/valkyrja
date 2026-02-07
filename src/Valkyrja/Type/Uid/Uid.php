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

namespace Valkyrja\Type\Uid;

use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\Uid\Contract\UidContract;
use Valkyrja\Type\Uid\Factory\UidFactory;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;

use function gettype;
use function is_string;
use function sprintf;

/**
 * @extends Type<string>
 */
class Uid extends Type implements UidContract
{
    /**
     * @throws InvalidUlidException
     */
    public function __construct(string $subject)
    {
        UidFactory::validate($subject);

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
            throw new InvalidArgumentException(sprintf('String expected value of type `%s` provided', gettype($value)));
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
