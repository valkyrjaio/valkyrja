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

namespace Valkyrja\Http\Message\Param;

use Override;
use Valkyrja\Http\Message\Param\Abstract\ParamCollection;
use Valkyrja\Http\Message\Param\Contract\AttributeParamCollectionContract;

/**
 * @extends ParamCollection<non-empty-string|int, scalar|AttributeParamCollectionContract|null>
 */
class AttributeParamCollection extends ParamCollection implements AttributeParamCollectionContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string|int $key): AttributeParamCollectionContract|float|bool|int|string|null
    {
        return $this->params[$key]
            ?? null;
    }
}
