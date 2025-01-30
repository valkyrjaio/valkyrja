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

namespace Valkyrja\Auth\Policy;

use Valkyrja\Auth\Policy\Contract\EntityPolicy as Contract;
use Valkyrja\Auth\Repository\Contract\Repository;
use Valkyrja\Orm\Entity\Contract\Entity;

/**
 * Abstract Class EntityPolicy.
 *
 * @author Melech Mizrachi
 */
abstract class EntityPolicy extends Policy implements Contract
{
    /**
     * The entity class name.
     *
     * @var class-string<Entity>
     */
    protected static string $entityClassName;

    /**
     * Policy constructor.
     *
     * @param Repository $repository The repository
     * @param Entity     $entity     The entity
     */
    public function __construct(
        Repository $repository,
        protected Entity $entity
    ) {
        parent::__construct($repository);
    }

    /**
     * @inheritDoc
     *
     * @return class-string<Entity>
     */
    public static function getEntityClassName(): string
    {
        return static::$entityClassName;
    }
}
