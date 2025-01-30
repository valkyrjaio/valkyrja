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

use Valkyrja\Type\BuiltIn\Contract\NullT as Contract;
use Valkyrja\Type\Type;

/**
 * Class NullT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<null>
 */
class NullT extends Type implements Contract
{
    public function __construct()
    {
        $this->subject = null;
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
    public function asValue(): mixed
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string|int|float|bool|null
    {
        return null;
    }
}
