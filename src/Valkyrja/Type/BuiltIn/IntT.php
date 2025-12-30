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

namespace Valkyrja\Type\BuiltIn;

use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\BuiltIn\Contract\IntT as Contract;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;

use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * Class IntT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<int>
 */
class IntT extends Type implements Contract
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
            is_int($value)   => new static($value),
            is_string($value), is_float($value), is_bool($value) => new static((int) $value),
            is_array($value) => new static($value !== [] ? 1 : 0),
            default          => throw new InvalidArgumentException('Unsupported value provided'),
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
