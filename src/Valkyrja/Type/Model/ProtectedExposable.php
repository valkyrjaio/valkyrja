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

namespace Valkyrja\Type\Model;

use Valkyrja\Type\BuiltIn\Support\Obj;

/**
 * Trait ProtectedExposable.
 *
 * @author Melech Mizrachi
 */
trait ProtectedExposable
{
    use Exposable;

    /**
     * Get all properties.
     *
     * @return array<string, mixed>
     */
    protected function internalGetAllProperties(): array
    {
        return array_merge(Obj::getProperties($this), $this->__exposed);
    }
}
