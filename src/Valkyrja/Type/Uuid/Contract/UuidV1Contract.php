<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Uuid\Contract;

use Override;

interface UuidV1Contract extends UuidContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string;

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string;
}
