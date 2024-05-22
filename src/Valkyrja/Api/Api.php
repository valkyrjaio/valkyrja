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

namespace Valkyrja\Api;

use Exception;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Contract\Api as Contract;
use Valkyrja\Api\Model\Contract\Json;
use Valkyrja\Api\Model\Contract\JsonData;
use Valkyrja\Http\Constant\StatusCode;
use Valkyrja\Http\Exception\HttpException;
use Valkyrja\Http\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Response\Contract\JsonResponse;
use Valkyrja\Orm\Entity;

use function end;
use function explode;
use function strtolower;

/**
 * Class Api.
 *
 * @author Melech Mizrachi
 */
class Api implements Contract
{
    /**
     * Api constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param Config|array    $config
     * @param bool            $debug [optional]
     */
    public function __construct(
        protected ResponseFactory $responseFactory,
        protected Config|array $config,
        protected bool $debug = false
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonFromException(Exception $exception): Json
    {
        $json = $this->getJsonModel();

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
     * @inheritDoc
     */
    public function jsonResponseFromException(Exception $exception): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromException($exception));
    }

    /**
     * @inheritDoc
     */
    public function jsonFromObject(object $object): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $jsonData->setItem($object);
        $this->setItemKeysFromObject($object, $jsonData);
        $json->setData($jsonData->asArray());

        return $json;
    }

    /**
     * @inheritDoc
     */
    public function jsonResponseFromObject(object $object): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromObject($object));
    }

    /**
     * @inheritDoc
     */
    public function jsonFromObjects(object ...$objects): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $jsonData->setItems($objects);

        if (! empty($objects)) {
            $this->setItemKeysFromObject($objects[0], $jsonData);
        }

        $json->setData($jsonData->asArray());

        return $json;
    }

    /**
     * @inheritDoc
     */
    public function jsonResponseFromObjects(object ...$objects): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromObjects(...$objects));
    }

    /**
     * @inheritDoc
     */
    public function jsonFromArray(array $array): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $jsonData->setData($array);

        $json->setData($jsonData->asArray());

        return $json;
    }

    /**
     * @inheritDoc
     */
    public function jsonResponseFromArray(array $array): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromArray($array));
    }

    /**
     * @inheritDoc
     */
    public function jsonFromEntity(Entity $entity): Json
    {
        return $this->jsonFromObject($entity);
    }

    /**
     * @inheritDoc
     */
    public function jsonResponseFromEntity(Entity $entity): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromEntity($entity));
    }

    /**
     * @inheritDoc
     */
    public function jsonFromEntities(Entity ...$entities): Json
    {
        return $this->jsonFromObjects(...$entities);
    }

    /**
     * @inheritDoc
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
        return $this->responseFactory->createJsonResponse($json->asArray());
    }

    /**
     * Get JSON model.
     *
     * @return Json
     */
    protected function getJsonModel(): Json
    {
        /** @var class-string<Json> $jsonModel */
        $jsonModel = $this->config['jsonModel'];

        return new $jsonModel();
    }

    /**
     * Get JSON data model.
     *
     * @return JsonData
     */
    protected function getJsonDataModel(): JsonData
    {
        /** @var class-string<JsonData> $jsonDataModel */
        $jsonDataModel = $this->config['jsonDataModel'];

        return new $jsonDataModel();
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
        $classNameParts = explode('\\', $object::class);

        return strtolower(end($classNameParts));
    }
}
