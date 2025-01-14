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

namespace Valkyrja\Test\Output;

use Valkyrja\Test\Result\Contract\Results;

/**
 * Class EchoSingleTestOutput.
 *
 * @author Melech Mizrachi
 */
class EchoSingleTestOutput extends EchoOutput
{
    /**
     * @inheritDoc
     */
    public function full(Results $results): void
    {
        $this->tests($results);
    }
}
