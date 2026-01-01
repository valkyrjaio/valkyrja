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

namespace Valkyrja\Cli\Routing\Data\Contract;

use Valkyrja\Cli\Interaction\Argument\Contract\ArgumentContract;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;

interface ArgumentParameterContract extends ParameterContract
{
    /**
     * Get the argument mode.
     *
     * @return ArgumentMode
     */
    public function getMode(): ArgumentMode;

    /**
     * Create a new Argument parameter with the specified argument mode.
     *
     * @param ArgumentMode $mode The argument mode
     *
     * @return static
     */
    public function withMode(ArgumentMode $mode): static;

    /**
     * Get the argument value mode.
     *
     * @return ArgumentValueMode
     */
    public function getValueMode(): ArgumentValueMode;

    /**
     * Create a new Argument parameter with the specified argument value mode.
     *
     * @param ArgumentValueMode $valueMode The argument value mode
     *
     * @return static
     */
    public function withValueMode(ArgumentValueMode $valueMode): static;

    /**
     * Get the arguments.
     *
     * @return ArgumentContract[]
     */
    public function getArguments(): array;

    /**
     * Create a new Argument parameter with the specified arguments.
     *
     * @param ArgumentContract ...$arguments The arguments
     *
     * @return static
     */
    public function withArguments(ArgumentContract ...$arguments): static;

    /**
     * Create a new Argument parameter with added arguments.
     *
     * @param ArgumentContract ...$arguments The arguments
     *
     * @return static
     */
    public function withAddedArguments(ArgumentContract ...$arguments): static;
}
