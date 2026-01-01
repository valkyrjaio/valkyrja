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

namespace Valkyrja\Api\Manager\Contract;

use Throwable;
use Valkyrja\Api\Model\Contract\JsonContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Orm\Entity\Contract\EntityContract;

/**
 * Interface ApiContract.
 *
 * @author Melech Mizrachi
 */
interface ApiContract
{
    /**
     * Make a new JSON model from an exception.
     *
     * @param Throwable $exception
     *
     * @return JsonContract
     */
    public function jsonFromException(Throwable $exception): JsonContract;

    /**
     * Make a new JSON response from an exception.
     *
     * @param Throwable $exception
     *
     * @return JsonResponseContract
     */
    public function jsonResponseFromException(Throwable $exception): JsonResponseContract;

    /**
     * Make a new JSON model from an object.
     *
     * @param object $object
     *
     * @return JsonContract
     */
    public function jsonFromObject(object $object): JsonContract;

    /**
     * Make a new JSON model from an object.
     *
     * @param object $object
     *
     * @return JsonResponseContract
     */
    public function jsonResponseFromObject(object $object): JsonResponseContract;

    /**
     * Make a new JSON model from an array of objects.
     *
     * @param object ...$objects
     *
     * @return JsonContract
     */
    public function jsonFromObjects(object ...$objects): JsonContract;

    /**
     * Make a new JSON response from an array of objects.
     *
     * @param object ...$objects
     *
     * @return JsonResponseContract
     */
    public function jsonResponseFromObjects(object ...$objects): JsonResponseContract;

    /**
     * Make a new JSON model from an array.
     *
     * @param array<array-key, mixed> $array
     *
     * @return JsonContract
     */
    public function jsonFromArray(array $array): JsonContract;

    /**
     * Make a new JSON model from an array.
     *
     * @param array<array-key, mixed> $array
     *
     * @return JsonResponseContract
     */
    public function jsonResponseFromArray(array $array): JsonResponseContract;

    /**
     * Make a new JSON model from an entity.
     *
     * @param EntityContract $entity
     *
     * @return JsonContract
     */
    public function jsonFromEntity(EntityContract $entity): JsonContract;

    /**
     * Make a new JSON response from an entity.
     *
     * @param EntityContract $entity
     *
     * @return JsonResponseContract
     */
    public function jsonResponseFromEntity(EntityContract $entity): JsonResponseContract;

    /**
     * Make a new JSON model from an array of entities.
     *
     * @param EntityContract ...$entities
     *
     * @return JsonContract
     */
    public function jsonFromEntities(EntityContract ...$entities): JsonContract;

    /**
     * Make a new JSON response from an array of entities.
     *
     * @param EntityContract ...$entities
     *
     * @return JsonResponseContract
     */
    public function jsonResponseFromEntities(EntityContract ...$entities): JsonResponseContract;
}
