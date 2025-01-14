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

use Valkyrja\Type\BuiltIn\Contract\FalseT as Contract;
use Valkyrja\Type\Type;

/**
 * Class FalseT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<false>
 *
 * @phpstan-consistent-constructor
 *   Will be overridden if need be
 */
class FalseT extends Type implements Contract
{
    public function __construct()
    {
        parent::__construct(false);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static();
    }

    /**
     * @inheritDoc
     */
    public function asValue(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): bool
    {
        return false;
    }
}
