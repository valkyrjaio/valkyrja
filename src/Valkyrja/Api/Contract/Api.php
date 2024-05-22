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

namespace Valkyrja\Api\Contract;

use Exception;
use Valkyrja\Api\Model\Contract\Json;
use Valkyrja\Http\Response\Contract\JsonResponse;
use Valkyrja\Orm\Entity;

/**
 * Interface Api.
 *
 * @author Melech Mizrachi
 */
interface Api
{
    /**
     * Make a new JSON model from an exception.
     *
     * @param Exception $exception
     *
     * @return Json
     */
    public function jsonFromException(Exception $exception): Json;

    /**
     * Make a new JSON response from an exception.
     *
     * @param Exception $exception
     *
     * @return JsonResponse
     */
    public function jsonResponseFromException(Exception $exception): JsonResponse;

    /**
     * Make a new JSON model from an object.
     *
     * @param object $object
     *
     * @return Json
     */
    public function jsonFromObject(object $object): Json;

    /**
     * Make a new JSON model from an object.
     *
     * @param object $object
     *
     * @return JsonResponse
     */
    public function jsonResponseFromObject(object $object): JsonResponse;

    /**
     * Make a new JSON model from an array of objects.
     *
     * @param object ...$objects
     *
     * @return Json
     */
    public function jsonFromObjects(object ...$objects): Json;

    /**
     * Make a new JSON response from an array of objects.
     *
     * @param object ...$objects
     *
     * @return JsonResponse
     */
    public function jsonResponseFromObjects(object ...$objects): JsonResponse;

    /**
     * Make a new JSON model from an array.
     *
     * @param array $array
     *
     * @return Json
     */
    public function jsonFromArray(array $array): Json;

    /**
     * Make a new JSON model from an array.
     *
     * @param array $array
     *
     * @return JsonResponse
     */
    public function jsonResponseFromArray(array $array): JsonResponse;

    /**
     * Make a new JSON model from an entity.
     *
     * @param Entity $entity
     *
     * @return Json
     */
    public function jsonFromEntity(Entity $entity): Json;

    /**
     * Make a new JSON response from an entity.
     *
     * @param Entity $entity
     *
     * @return JsonResponse
     */
    public function jsonResponseFromEntity(Entity $entity): JsonResponse;

    /**
     * Make a new JSON model from an array of entities.
     *
     * @param Entity ...$entities
     *
     * @return Json
     */
    public function jsonFromEntities(Entity ...$entities): Json;

    /**
     * Make a new JSON response from an array of entities.
     *
     * @param Entity ...$entities
     *
     * @return JsonResponse
     */
    public function jsonResponseFromEntities(Entity ...$entities): JsonResponse;
}
