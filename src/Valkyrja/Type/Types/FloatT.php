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

namespace Valkyrja\Type\Types;

use Valkyrja\Type\FloatT as Contract;

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
        return new static((float) $value);
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
