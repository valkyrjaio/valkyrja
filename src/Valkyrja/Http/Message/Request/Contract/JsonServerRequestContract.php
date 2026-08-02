<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Request\Contract;

use Valkyrja\Http\Message\Param\Contract\ParsedJsonParamCollectionContract;

interface JsonServerRequestContract extends ServerRequestContract
{
    /**
     * Get the parsed JSON body parameters.
     */
    public function getParsedJson(): ParsedJsonParamCollectionContract;

    /**
     * Create a new instance with the specified parsed JSON body parameters.
     */
    public function withParsedJson(ParsedJsonParamCollectionContract $params): static;
}
