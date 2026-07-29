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

namespace Valkyrja\Tests\Fixtures\Cli\Middleware\Trait;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;

/**
 * Input-received middleware either passes an input along or short-circuits with an
 * output, so its return type is a union. Tests that expect one of the two narrow
 * through these rather than calling the expected side's methods on the union.
 */
trait InputReceivedResultTrait
{
    /**
     * Get a passed-along input, failing the test when the middleware short-circuited.
     */
    protected static function inputFrom(InputContract|OutputContract $result): InputContract
    {
        if (! $result instanceof InputContract) {
            self::fail('Expected the middleware to pass an input along.');
        }

        return $result;
    }

    /**
     * Get a short-circuiting output, failing the test when the middleware passed an input along.
     */
    protected static function outputFrom(InputContract|OutputContract $result): OutputContract
    {
        if (! $result instanceof OutputContract) {
            self::fail('Expected the middleware to short-circuit with an output.');
        }

        return $result;
    }
}
