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

namespace Valkyrja\Http\Message\Uri\Type;

use Valkyrja\Http\Message\Exception\InvalidArgumentException;
use Valkyrja\Type\Type;

/**
 * Class Port.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<int|null>
 */
class Port extends Type
{
    public function __construct(int|null $subject)
    {
        if ($subject === null || ($subject >= 1 && $subject <= 65535)) {
            $this->subject = $subject;

            return;
        }

        throw new InvalidArgumentException('Invalid port argument passed.');
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        if ($value !== null && ! is_int($value)) {
            throw new InvalidArgumentException(sprintf('Int or null expected value of type `%s` provided', gettype($value)));
        }

        return new static($value);
    }

    /**
     * @inheritDoc
     *
     * @return int|null
     */
    public function asFlatValue(): int|null
    {
        return $this->subject;
    }
}
