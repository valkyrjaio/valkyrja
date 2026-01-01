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

namespace Valkyrja\Type\BuiltIn\Enum\Contract;

use BackedEnum;
use Override;
use Valkyrja\Type\Contract\TypeContract;

/**
 * Interface BackedEnumContract.
 *
 * @author Melech Mizrachi
 *
 * @extends TypeContract<static>
 */
interface BackedEnumContract extends TypeContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): BackedEnum;
}
