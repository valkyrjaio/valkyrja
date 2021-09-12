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

namespace Valkyrja\Api\Facades;

use Exception;
use Valkyrja\Api\Api as Contract;
use Valkyrja\Api\Json;
use Valkyrja\Http\JsonResponse;
use Valkyrja\ORM\Entity;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Api.
 *
 * @author Melech Mizrachi
 *
 * @method static Json jsonFromException(Exception $exception)
 * @method static JsonResponse jsonResponseFromException(Exception $exception)
 * @method static Json jsonFromObject(object $object)
 * @method static JsonResponse jsonResponseFromObject(object $object)
 * @method static Json jsonFromObjects(object ...$objects)
 * @method static JsonResponse jsonResponseFromObjects(object ...$objects)
 * @method static Json jsonFromArray(array $array)
 * @method static JsonResponse jsonResponseFromArray(array $array)
 * @method static Json jsonFromEntity(Entity $entity)
 * @method static JsonResponse jsonResponseFromEntity(Entity $entity)
 * @method static Json jsonFromEntities(Entity ...$entities)
 * @method static JsonResponse jsonResponseFromEntities(Entity ...$entities)
 */
class Api extends Facade
{
    /**
     * @inheritDoc
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
