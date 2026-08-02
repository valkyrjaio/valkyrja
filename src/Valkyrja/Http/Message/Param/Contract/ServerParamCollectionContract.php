<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Param\Contract;

use Override;

/**
 * @extends ParamCollectionContract<non-empty-string|int, scalar|ServerParamCollectionContract>
 */
interface ServerParamCollectionContract extends ParamCollectionContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string|int $key): self|float|bool|int|string;
}
