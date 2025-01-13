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

namespace Valkyrja\Dispatcher\Data;

use Valkyrja\Dispatcher\Data\Contract\GlobalVariableDispatch as Contract;

/**
 * Class GlobalVariableDispatch.
 *
 * @author Melech Mizrachi
 */
class GlobalVariableDispatch extends Dispatch implements Contract
{
    public function __construct(
        protected string $variable
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }

    /**
     * @inheritDoc
     */
    public function getVariable(): string
    {
        return $this->variable;
    }

    /**
     * @inheritDoc
     */
    public function withVariable(string $variable): static
    {
        $new = clone $this;

        $new->variable = $variable;

        return $new;
    }
}
