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

use Override;
use Valkyrja\Type\Contract\Type;

/**
 * Interface TrueT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<true>
 */
interface TrueT extends Type
{
    /**
     * @inheritDoc
     *
     * @return true
     */
    #[Override]
    public function asValue(): bool;

    /**
     * @inheritDoc
     *
     * @return true
     */
    #[Override]
    public function asFlatValue(): bool;
}
