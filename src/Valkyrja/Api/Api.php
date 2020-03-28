<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Api;

use Exception;
use Valkyrja\Model\Model;
use Valkyrja\ORM\Entity;

/**
 * Interface Api.
 *
 * @author Melech Mizrachi
 */
interface Api
{
    /**
     * Make a new JSON response model from an exception.
     *
     * @param Exception $exception
     *
     * @return Json
     */
    public function jsonFromException(Exception $exception): Json;

    /**
     * Make a new JSON response model from an object.
     *
     * @param object $object
     *
     * @return Json
     */
    public function jsonFromObject(object $object): Json;

    /**
     * Make a new JSON response model from an array of objects.
     *
     * @param object ...$objects
     *
     * @return Json
     */
    public function jsonFromObjects(object ...$objects): Json;

    /**
     * Make a new JSON response model from an entity.
     *
     * @param Entity $entity
     *
     * @return Json
     */
    public function jsonFromEntity(Entity $entity): Json;

    /**
     * Make a new JSON response model from an array of entities.
     *
     * @param Entity ...$entities
     *
     * @return Json
     */
    public function jsonFromEntities(Entity ...$entities): Json;
}
