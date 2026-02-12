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
