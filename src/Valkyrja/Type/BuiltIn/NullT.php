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
use Valkyrja\Type\BuiltIn\Contract\NullContract as Contract;

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
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): mixed
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string|int|float|bool|null
    {
        return null;
    }
}
