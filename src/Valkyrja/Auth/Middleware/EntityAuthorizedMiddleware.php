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

namespace Valkyrja\Auth\Middleware;

use Valkyrja\Auth\User;
use Valkyrja\ORM\Entity;

/**
 * Abstract Class EntityAuthorizedMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class EntityAuthorizedMiddleware extends AuthorizedMiddleware
{
    /**
     * The entity route param name.
     *
     * @var string
     */
    protected static string $entityRouteParamName;

    /**
     * The entity to authorize against.
     *
     * @var string
     */
    protected static string $entityName;

    /**
     * Check if the authenticated user is authorized.
     *
     * @param User $user The authed user
     *
     * @return bool
     */
    protected static function checkAuthorized(User $user): bool
    {
        /** @var Entity $entity */
        $entity = self::$container->getSingleton(
            static::getEntityServiceId()
        );

        return static::checkEntityAuthorized($user, $entity);
    }

    /**
     * Get the entity route param name. Method exists in case complex functionality is required to get the param name.
     *
     * @return string
     */
    protected static function getEntityRouteParamName(): string
    {
        return static::$entityRouteParamName;
    }

    /**
     * Get the entity name. Method exists in case complex functionality is required to get the name.
     *
     * @return string
     */
    protected static function getEntityName(): string
    {
        return static::$entityName;
    }

    /**
     * Get the entity service id. Method exists in case complex relationships and functionality exist for this entity.
     *
     * @return string
     */
    protected static function getEntityServiceId(): string
    {
        return static::getEntityName() . '0';
    }

    /**
     * Check if the authenticated user is authorized for the entity.
     *
     * @param User   $user   The authenticated user
     * @param Entity $entity The entity to ensure authorized
     *
     * @return bool
     */
    abstract protected static function checkEntityAuthorized(User $user, Entity $entity): bool;
}
