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
use Valkyrja\Http\Message\Param\Contract\ServerParamCollectionContract;

/**
 * @extends ParamCollection<non-empty-string|int, scalar|ServerParamCollectionContract>
 */
class ServerParamCollection extends ParamCollection implements ServerParamCollectionContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string|int $key): ServerParamCollectionContract|float|bool|int|string
    {
        return $this->params[$key]
            ?? '';
    }
}
