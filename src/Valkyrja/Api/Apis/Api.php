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

namespace Valkyrja\Api\Apis;

use Exception;
use Valkyrja\Api\Api as Contract;
use Valkyrja\Api\Constants\Status;
use Valkyrja\Api\Json;
use Valkyrja\Api\JsonData;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\ORM\Entity;

use function end;
use function explode;
use function get_class;
use function strtolower;

/**
 * Class Api.
 *
 * @author Melech Mizrachi
 */
class Api implements Contract
{
    /**
     * The json response.
     *
     * @var JsonResponse
     */
    protected JsonResponse $jsonResponse;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Whether to run in debug mode.
     *
     * @var bool
     */
    protected bool $debug = false;

    /**
     * Api constructor.
     *
     * @param JsonResponse $jsonResponse
     * @param array        $config
     * @param bool         $debug [optional]
     */
    public function __construct(JsonResponse $jsonResponse, array $config, bool $debug = false)
    {
        $this->jsonResponse = $jsonResponse;
        $this->config       = $config;
        $this->debug        = $debug;
    }

    /**
     * Make a new JSON model from an exception.
     *
     * @param Exception $exception
     *
     * @return Json
     */
    public function jsonFromException(Exception $exception): Json
    {
        $json     = $this->getJsonModel();

        $data = [
            'code' => $exception->getCode(),
        ];

        $json->setMessage($exception->getMessage());
        $json->setStatus(Status::ERROR);
        $json->setStatusCode(StatusCode::INTERNAL_SERVER_ERROR);

        if ($this->debug) {
            $data['file']  = $exception->getFile();
            $data['line']  = $exception->getLine();
            $data['trace'] = $exception->getTrace();
        }

        $json->setData($data);

        if ($exception instanceof HttpException) {
            $json->setStatusCode($exception->getStatusCode());
        }

        return $json;
    }

    /**
     * Make a new JSON response from an exception
     *
     * @param Exception $exception
     *
     * @return JsonResponse
     */
    public function jsonResponseFromException(Exception $exception): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromException($exception));
    }

    /**
     * Make a new JSON model from an object.
     *
     * @param object $object
     *
     * @return Json
     */
    public function jsonFromObject(object $object): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $jsonData->setItem($object);
        $this->setItemKeysFromObject($object, $jsonData);
        $json->setData($jsonData->__toArray());

        return $json;
    }

    /**
     * Make a new JSON model from an object.
     *
     * @param object $object
     *
     * @return JsonResponse
     */
    public function jsonResponseFromObject(object $object): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromObject($object));
    }

    /**
     * Make a new JSON model from an array of objects.
     *
     * @param object ...$objects
     *
     * @return Json
     */
    public function jsonFromObjects(object ...$objects): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $jsonData->setItems($objects);

        if (! empty($objects)) {
            $this->setItemKeysFromObject($objects[0], $jsonData);
        }

        $json->setData($jsonData->__toArray());

        return $json;
    }

    /**
     * Make a new JSON response from an array of objects.
     *
     * @param object ...$objects
     *
     * @return JsonResponse
     */
    public function jsonResponseFromObjects(object ...$objects): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromObjects(...$objects));
    }

    /**
     * Make a new JSON model from an array.
     *
     * @param array $array
     *
     * @return Json
     */
    public function jsonFromArray(array $array): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $jsonData->setData($array);

        $json->setData($jsonData->__toArray());

        return $json;
    }

    /**
     * Make a new JSON model from an array.
     *
     * @param array $array
     *
     * @return JsonResponse
     */
    public function jsonResponseFromArray(array $array): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromArray($array));
    }

    /**
     * Make a new JSON model from an entity.
     *
     * @param Entity $entity
     *
     * @return Json
     */
    public function jsonFromEntity(Entity $entity): Json
    {
        return $this->jsonFromObject($entity);
    }

    /**
     * Make a new JSON response from an entity.
     *
     * @param Entity $entity
     *
     * @return JsonResponse
     */
    public function jsonResponseFromEntity(Entity $entity): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromEntity($entity));
    }

    /**
     * Make a new JSON model from an array of entities.
     *
     * @param Entity ...$entities
     *
     * @return Json
     */
    public function jsonFromEntities(Entity ...$entities): Json
    {
        return $this->jsonFromObjects(...$entities);
    }

    /**
     * Make a new JSON response from an array of entities.
     *
     * @param Entity ...$entities
     *
     * @return JsonResponse
     */
    public function jsonResponseFromEntities(Entity ...$entities): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromEntities(...$entities));
    }

    /**
     * Get a JSON response from a JSON model.
     *
     * @param Json $json The json model
     *
     * @return JsonResponse
     */
    protected function getResponseFromModel(Json $json): JsonResponse
    {
        return $this->jsonResponse::createFromData($json->__toArray());
    }

    /**
     * Get JSON model.
     *
     * @return Json
     */
    protected function getJsonModel(): Json
    {
        return new $this->config['jsonModel']();
    }

    /**
     * Get JSON data model.
     *
     * @return JsonData
     */
    protected function getJsonDataModel(): JsonData
    {
        return new $this->config['jsonDataModel']();
    }

    /**
     * Set item keys from object.
     *
     * @param object   $object
     * @param JsonData $jsonData
     *
     * @return void
     */
    protected function setItemKeysFromObject(object $object, JsonData $jsonData): void
    {
        $className = $this->getClassNameFromObject($object);

        $jsonData->setItemKey($className);
        $jsonData->setItemsKey($className . 's');
    }

    /**
     * Get the class name from an object.
     *
     * @param object $object
     *
     * @return string
     */
    protected function getClassNameFromObject(object $object): string
    {
        $classNameParts = explode('\\', get_class($object));

        return strtolower(end($classNameParts));
    }
}
