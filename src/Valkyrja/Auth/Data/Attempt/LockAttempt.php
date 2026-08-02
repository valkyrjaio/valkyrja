<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Data\Attempt;

use Override;
use Valkyrja\Auth\Data\Attempt\Contract\LockAttemptContract;
use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;

class LockAttempt implements LockAttemptContract
{
    public function __construct(
        protected RetrievalContract $retrieval,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRetrieval(): RetrievalContract
    {
        return $this->retrieval;
    }
}
