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

use Valkyrja\Type\TrueT as Contract;

/**
 * Class TrueT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<true>
 */
class TrueT extends Type implements Contract
{
    public function __construct(bool $subject = null)
    {
        parent::__construct(true);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static(true);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): bool
    {
        return true;
    }
}
