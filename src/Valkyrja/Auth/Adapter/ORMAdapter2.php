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

namespace Valkyrja\Auth\Adapter;

use Valkyrja\Auth\Config;
use Valkyrja\Auth\Data\Contract\AuthenticationAttempt;
use Valkyrja\Auth\Data\Contract\AuthenticationRetrieval;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Orm\Orm;

/**
 * Class ORMAdapter.
 *
 * @author Melech Mizrachi
 */
abstract class ORMAdapter2 extends Adapter2
{
    /**
     * ORMAdapter constructor.
     *
     * @param class-string<User> $user
     */
    public function __construct(
        protected Orm $orm,
        string $user,
        Config|array $config
    ) {
        parent::__construct($user, $config);
    }

    /**
     * @inheritDoc
     */
    public function authenticate(AuthenticationAttempt $attempt): User|null
    {
        // TODO: Implement authenticate() method.
    }

    /**
     * @inheritDoc
     */
    public function retrieve(AuthenticationRetrieval $retrieval): User|null
    {
        // TODO: Implement retrieve() method.
    }

    /**
     * @inheritDoc
     */
    public function create(User $user): bool
    {
        // TODO: Implement create() method.
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        // TODO: Implement save() method.
    }
}
