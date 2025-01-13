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

namespace Valkyrja\Http\Routing\Data\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;

/**
 * Interface Redirect.
 *
 * @author Melech Mizrachi
 */
interface Redirect extends Route
{
    /**
     * Get the redirect path.
     *
     * @return non-empty-string
     */
    public function getRedirectPath(): string;

    /**
     * Create a new route with a specified redirect path.
     *
     * @param non-empty-string $to The path to redirect to
     *
     * @return static
     */
    public function withRedirectPath(string $to): static;

    /**
     * Get the status code.
     *
     * @return StatusCode
     */
    public function getStatusCode(): StatusCode;

    /**
     * Create a new route with the specified status code.
     *
     * @param StatusCode $code The status code
     *
     * @return static
     */
    public function withStatusCode(StatusCode $code): static;
}
