<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Data;

use Override;
use Valkyrja\Dispatch\Data\Abstract\Dispatch;
use Valkyrja\Dispatch\Data\Contract\GlobalVariableDispatchContract;

class GlobalVariableDispatch extends Dispatch implements GlobalVariableDispatchContract
{
    /**
     * @param non-empty-string $variable The variable name
     */
    public function __construct(
        protected string $variable
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getVariable(): string
    {
        return $this->variable;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withVariable(string $variable): static
    {
        $new = clone $this;

        $new->variable = $variable;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __toString(): string
    {
        return $this->variable;
    }
}
