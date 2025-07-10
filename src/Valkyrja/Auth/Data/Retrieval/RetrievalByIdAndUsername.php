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

namespace Valkyrja\Auth\Data\Retrieval;

use Override;
use Valkyrja\Auth\Data\Retrieval\Contract\Retrieval as Contract;
use Valkyrja\Auth\Entity\Contract\User;

/**
 * Class RetrievalByIdAndUsername.
 *
 * @author Melech Mizrachi
 */
class RetrievalByIdAndUsername implements Contract
{
    /**
     * @param non-empty-string|int $id       The id
     * @param non-empty-string     $username The username
     */
    public function __construct(
        protected string|int $id,
        protected string $username,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @param class-string<User> $user The user
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    #[Override]
    public function getRetrievalFields(string $user): array
    {
        return [
            $user::getIdField()       => $this->id,
            $user::getUsernameField() => $this->username,
        ];
    }
}
