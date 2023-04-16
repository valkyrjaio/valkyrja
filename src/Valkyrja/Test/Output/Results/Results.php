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

namespace Valkyrja\Test\Output\Results;

use Valkyrja\Test\Output\Results as Contract;
use Valkyrja\Test\Test;

/**
 * Class Results.
 *
 * @author Melech Mizrachi
 */
class Results implements Contract
{
    /**
     * @param Test[] $results The results
     */
    public function __construct(
        protected array $results = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getTests(): array
    {
        return $this->results;
    }
}
