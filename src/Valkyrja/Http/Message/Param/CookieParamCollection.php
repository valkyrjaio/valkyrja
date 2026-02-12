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
use Valkyrja\Http\Message\Param\Contract\CookieParamCollectionContract;

/**
 * @extends ParamCollection<non-empty-string, string>
 */
class CookieParamCollection extends ParamCollection implements CookieParamCollectionContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getParam(string|int $name): string|null
    {
        return $this->params[$name]
            ?? null;
    }
}
