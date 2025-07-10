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

namespace Valkyrja\Type\Id;

use Override;
use Valkyrja\Type\Exception\InvalidArgumentException;
use Valkyrja\Type\Id\Contract\IntId as Contract;
use Valkyrja\Type\Type;

use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * Class IntId.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<int>
 */
class IntId extends Type implements Contract
{
    public function __construct(int $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return match (true) {
            is_int($value) => new static($value),
            is_string($value), is_float($value), is_bool($value) => new static((int) $value),
            default        => throw new InvalidArgumentException('Unsupported value provided'),
        };
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): int
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): int
    {
        return $this->asValue();
    }
}
