<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
