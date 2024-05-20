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

namespace Valkyrja\Type\BuiltIn\Contract;

use Valkyrja\Type\Contract\Type;

/**
 * Interface ObjectT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<object>
 */
interface ObjectT extends Type
{
    /**
     * @inheritDoc
     */
    public function asValue(): object;

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string;
}
