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

namespace Valkyrja\Http;

use Valkyrja\Validation\Validator;

/**
 * Interface ValidateRequest.
 *
 * @author Melech Mizrachi
 */
interface ValidateRequest
{
    /**
     * Validate the request.
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * Get the request.
     *
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * Get the validator.
     *
     * @return Validator
     */
    public function getValidator(): Validator;
}
