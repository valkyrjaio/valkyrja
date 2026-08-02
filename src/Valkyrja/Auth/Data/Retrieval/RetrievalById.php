<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Data\Retrieval;

use Override;
use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;
use Valkyrja\Auth\Entity\Contract\UserContract;

class RetrievalById implements RetrievalContract
{
    /**
     * @param non-empty-string|int $id The id
     */
    public function __construct(
        protected string|int $id,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @param class-string<UserContract> $user The user
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    #[Override]
    public function getRetrievalFields(string $user): array
    {
        return [
            $user::getIdField() => $this->id,
        ];
    }
}
