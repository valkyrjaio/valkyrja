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

use Valkyrja\Type\NullT as Contract;

/**
 * Class NullT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<null>
 */
class NullT extends Type implements Contract
{
    public function __construct(mixed $subject = null)
    {
        parent::__construct(null);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static(null);
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
