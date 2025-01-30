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

use Valkyrja\Type\BuiltIn\Contract\FloatT as Contract;
use Valkyrja\Type\Exception\InvalidArgumentException;
use Valkyrja\Type\Type;

use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * Class FloatT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<float>
 */
class FloatT extends Type implements Contract
{
    public function __construct(float $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return match (true) {
            is_float($value) => new static($value),
            is_string($value), is_int($value), is_bool($value) => new static((float) $value),
            is_array($value) => new static($value !== [] ? 1.0 : 0.0),
            default          => throw new InvalidArgumentException('Unsupported value provided'),
        };
    }

    /**
     * @inheritDoc
     */
    public function asValue(): float
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): float
    {
        return $this->asValue();
    }
}
