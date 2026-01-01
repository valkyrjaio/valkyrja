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

namespace Valkyrja\Dispatch\Data\Contract;

/**
 * Interface GlobalVariableDispatchContract.
 *
 * @author Melech Mizrachi
 */
interface GlobalVariableDispatchContract extends DispatchContract
{
    /**
     * Get the variable.
     *
     * @return non-empty-string
     */
    public function getVariable(): string;

    /**
     * Create a new dispatch with the specified variable.
     *
     * @param non-empty-string $variable
     *
     * @return static
     */
    public function withVariable(string $variable): static;
}
