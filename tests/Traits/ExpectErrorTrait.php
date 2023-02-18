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

namespace Valkyrja\Tests\Traits;

use Valkyrja\Exception\Exception;

use const E_USER_WARNING;

/**
 * Test case for unit tests.
 *
 * @author Melech Mizrachi
 */
trait ExpectErrorTrait
{
    protected function setupErrorHandler(): void
    {
        set_error_handler(
            static function (int $code, string $message): never {
                throw new Exception($message, $code);
            },
            E_USER_WARNING
        );
    }
}
