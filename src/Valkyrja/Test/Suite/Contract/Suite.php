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

namespace Valkyrja\Test\Suite\Contract;

/**
 * Interface Suite.
 *
 * @author Melech Mizrachi
 */
interface Suite
{
    /**
     * Run the suite.
     *
     * @param string[]|null $args The arguments
     */
    public function run(?array $args = null): void;
}
