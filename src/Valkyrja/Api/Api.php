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

use Override;
use Throwable;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Contract\Api as Contract;
use Valkyrja\Api\Model\Contract\Json;
use Valkyrja\Api\Model\Contract\JsonData;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Exception\HttpException;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Response\Contract\JsonResponse;
use Valkyrja\Orm\Entity\Contract\Entity;

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
     */
    public function __construct(
        protected ResponseFactory $responseFactory,
        protected bool $debug = false
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromException(Throwable $exception): Json
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
    public function jsonResponseFromException(Throwable $exception): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromException($exception));
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function jsonResponseFromObject(object $object): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromObject($object));
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function jsonResponseFromObjects(object ...$objects): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromObjects(...$objects));
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function jsonResponseFromArray(array $array): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromArray($array));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromEntity(Entity $entity): Json
    {
        return $this->jsonFromObject($entity);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonResponseFromEntity(Entity $entity): JsonResponse
    {
        return $this->getResponseFromModel($this->jsonFromEntity($entity));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonFromEntities(Entity ...$entities): Json
    {
        return $this->jsonFromObjects(...$entities);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
        return new Model\Json();
    }

    /**
     * Get JSON data model.
     *
     * @return JsonData
     */
    protected function getJsonDataModel(): JsonData
    {
        return new Model\JsonData();
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
