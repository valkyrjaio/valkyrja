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
use Valkyrja\Type\BuiltIn\Contract\TrueT as Contract;
use Valkyrja\Type\Type;

/**
 * Class TrueT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<true>
 */
class TrueT extends Type implements Contract
{
    public function __construct()
    {
        $this->subject = true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): bool
    {
        return true;
    }
}
