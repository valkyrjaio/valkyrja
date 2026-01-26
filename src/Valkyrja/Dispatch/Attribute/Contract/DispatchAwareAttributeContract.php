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

namespace Valkyrja\Dispatch\Attribute\Contract;

use Valkyrja\Dispatch\Data\Contract\DispatchContract;

interface DispatchAwareAttributeContract
{
    /**
     * @return non-empty-string
     */
    public function getName(): string;

    public function getDispatch(): DispatchContract;

    public function withDispatch(DispatchContract $contract): static;
}
