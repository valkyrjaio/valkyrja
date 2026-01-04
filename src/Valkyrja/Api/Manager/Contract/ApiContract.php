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

interface ApiContract
{
    /**
     * Make a new JSON model from an exception.
     *
     *
     */
    public function jsonFromException(Throwable $exception): JsonContract;

    /**
     * Make a new JSON response from an exception.
     *
     *
     */
    public function jsonResponseFromException(Throwable $exception): JsonResponseContract;

    /**
     * Make a new JSON model from an object.
     *
     *
     */
    public function jsonFromObject(object $object): JsonContract;

    /**
     * Make a new JSON model from an object.
     *
     *
     */
    public function jsonResponseFromObject(object $object): JsonResponseContract;

    /**
     * Make a new JSON model from an array of objects.
     *
     *
     */
    public function jsonFromObjects(object ...$objects): JsonContract;

    /**
     * Make a new JSON response from an array of objects.
     *
     *
     */
    public function jsonResponseFromObjects(object ...$objects): JsonResponseContract;

    /**
     * Make a new JSON model from an array.
     *
     * @param array<array-key, mixed> $array
     */
    public function jsonFromArray(array $array): JsonContract;

    /**
     * Make a new JSON model from an array.
     *
     * @param array<array-key, mixed> $array
     */
    public function jsonResponseFromArray(array $array): JsonResponseContract;

    /**
     * Make a new JSON model from an entity.
     *
     *
     */
    public function jsonFromEntity(EntityContract $entity): JsonContract;

    /**
     * Make a new JSON response from an entity.
     *
     *
     */
    public function jsonResponseFromEntity(EntityContract $entity): JsonResponseContract;

    /**
     * Make a new JSON model from an array of entities.
     *
     *
     */
    public function jsonFromEntities(EntityContract ...$entities): JsonContract;

    /**
     * Make a new JSON response from an array of entities.
     *
     *
     */
    public function jsonResponseFromEntities(EntityContract ...$entities): JsonResponseContract;
}
