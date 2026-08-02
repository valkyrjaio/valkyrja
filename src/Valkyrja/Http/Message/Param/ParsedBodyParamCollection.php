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
use Valkyrja\Http\Message\Param\Contract\ParsedBodyParamCollectionContract;

/**
 * @extends ParamCollection<non-empty-string|int, string|ParsedBodyParamCollectionContract>
 */
class ParsedBodyParamCollection extends ParamCollection implements ParsedBodyParamCollectionContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string|int $key): ParsedBodyParamCollectionContract|string
    {
        return $this->params[$key]
            ?? '';
    }
}
