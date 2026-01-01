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

namespace Valkyrja\Http\Routing\Url\Contract;

/**
 * Interface UrlContract.
 */
interface UrlContract
{
    /**
     * Get a route url by name.
     *
     * @param non-empty-string               $name The name of the route to get
     * @param array<string, string|int>|null $data [optional] The route data if dynamic
     *
     * @return string
     */
    public function getUrl(string $name, array|null $data = null): string;
}
