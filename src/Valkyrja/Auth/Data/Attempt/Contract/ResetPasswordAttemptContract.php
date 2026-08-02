<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Data\Attempt\Contract;

use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;

interface ResetPasswordAttemptContract
{
    /**
     * Get the authentication retrieval.
     */
    public function getRetrieval(): RetrievalContract;

    /**
     * Get the password.
     */
    public function getPassword(): string;
}
