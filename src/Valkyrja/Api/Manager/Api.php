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

namespace Valkyrja\Api\Manager;

use Override;
use Throwable;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Manager\Contract\ApiContract as Contract;
use Valkyrja\Api\Model\Contract\JsonContract;
use Valkyrja\Api\Model\Contract\JsonDataContract;
use Valkyrja\Api\Model\Json as JsonModel;
use Valkyrja\Api\Model\JsonData as JsonDataModel;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Orm\Entity\Contract\EntityContract;

use function end;
use function explode;
use function strtolower;

class Api implements Contract
{
    public function __construct(
        protected ResponseFactoryContract $responseFactory,
        protected bool $debug = false
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromException(Throwable $exception): JsonContract
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
    #[Override]
    public function jsonResponseFromException(Throwable $exception): JsonResponseContract
    {
        return $this->getResponseFromModel($this->jsonFromException($exception));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromObject(object $object): JsonContract
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
    #[Override]
    public function jsonResponseFromObject(object $object): JsonResponseContract
    {
        return $this->getResponseFromModel($this->jsonFromObject($object));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromObjects(object ...$objects): JsonContract
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
    #[Override]
    public function jsonResponseFromObjects(object ...$objects): JsonResponseContract
    {
        return $this->getResponseFromModel($this->jsonFromObjects(...$objects));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromArray(array $array): JsonContract
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
    #[Override]
    public function jsonResponseFromArray(array $array): JsonResponseContract
    {
        return $this->getResponseFromModel($this->jsonFromArray($array));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromEntity(EntityContract $entity): JsonContract
    {
        return $this->jsonFromObject($entity);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonResponseFromEntity(EntityContract $entity): JsonResponseContract
    {
        return $this->getResponseFromModel($this->jsonFromEntity($entity));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromEntities(EntityContract ...$entities): JsonContract
    {
        return $this->jsonFromObjects(...$entities);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonResponseFromEntities(EntityContract ...$entities): JsonResponseContract
    {
        return $this->getResponseFromModel($this->jsonFromEntities(...$entities));
    }

    /**
     * Get a JSON response from a JSON model.
     *
     * @param JsonContract $json The json model
     */
    protected function getResponseFromModel(JsonContract $json): JsonResponseContract
    {
        return $this->responseFactory->createJsonResponse($json->asArray());
    }

    /**
     * Get JSON model.
     */
    protected function getJsonModel(): JsonContract
    {
        return new JsonModel();
    }

    /**
     * Get JSON data model.
     */
    protected function getJsonDataModel(): JsonDataContract
    {
        return new JsonDataModel();
    }

    /**
     * Set item keys from object.
     */
    protected function setItemKeysFromObject(object $object, JsonDataContract $jsonData): void
    {
        $className = $this->getClassNameFromObject($object);

        $jsonData->setItemKey($className);
        $jsonData->setItemsKey($className . 's');
    }

    /**
     * Get the class name from an object.
     */
    protected function getClassNameFromObject(object $object): string
    {
        $classNameParts = explode('\\', $object::class);

        return strtolower(end($classNameParts));
    }
}
