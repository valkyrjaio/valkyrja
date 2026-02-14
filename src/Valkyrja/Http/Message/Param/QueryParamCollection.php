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
use Valkyrja\Http\Message\Param\Contract\QueryParamCollectionContract;

/**
 * @extends ParamCollection<non-empty-string|int, string|QueryParamCollectionContract>
 */
class QueryParamCollection extends ParamCollection implements QueryParamCollectionContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string|int $key): QueryParamCollectionContract|string|null
    {
        return $this->params[$key]
            ?? null;
    }
}
