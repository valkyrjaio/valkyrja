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

namespace Valkyrja\Type\Uuid\Contract;

use Valkyrja\Type\Contract\Type;

/**
 * Interface Uuid.
 *
 * @author Melech Mizrachi
 *
 * @template T
 *
 * @extends Type<string|T>
 */
interface Uuid extends Type
{
    /**
     * @inheritDoc
     */
    public function asValue(): string;

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string;
}
