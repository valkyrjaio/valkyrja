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
use Valkyrja\Type\Contract\TypeContract;

/**
 * Interface FalseContract.
 *
 * @extends TypeContract<false>
 */
interface FalseContract extends TypeContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): bool;

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): bool;
}
