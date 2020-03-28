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

namespace Valkyrja\Api\Apis;

use Exception;
use Valkyrja\Api\Api as Contract;
use Valkyrja\Api\Enums\Status;
use Valkyrja\Api\Json;
use Valkyrja\Api\JsonData;
use Valkyrja\Api\Models\Json as JsonClass;
use Valkyrja\Api\Models\JsonData as JsonDataClass;
use Valkyrja\Application\Application;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\ORM\Entity;

use Valkyrja\Support\Providers\Provides;

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
    use Provides;

    /**
     * Whether to run in debug mode.
     *
     * @var bool
     */
    protected bool $debug = false;

    /**
     * Api constructor.
     *
     * @param bool $debug [optional]
     */
    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(
            Contract::class,
            new static($app->debug())
        );
    }

    /**
     * Make a new JSON response model from an exception.
     *
     * @param Exception $exception
     *
     * @return Json
     */
    public function jsonFromException(Exception $exception): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $json->setData($jsonData);

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

        $jsonData->setData($data);

        if ($exception instanceof HttpException) {
            $json->setStatusCode($exception->getStatusCode());
        }

        return $json;
    }

    /**
     * Make a new JSON response model from an object.
     *
     * @param object $object
     *
     * @return Json
     */
    public function jsonFromObject(object $object): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $json->setData($jsonData);

        $jsonData->setItem($object);

        $this->setItemKeysFromObject($object, $jsonData);

        return $json;
    }

    /**
     * Make a new JSON response model from an array of objects.
     *
     * @param object ...$objects
     *
     * @return Json
     */
    public function jsonFromObjects(object ...$objects): Json
    {
        $json     = $this->getJsonModel();
        $jsonData = $this->getJsonDataModel();

        $json->setData($jsonData);

        $jsonData->setItems($objects);

        if ($objects) {
            $this->setItemKeysFromObject($objects[0], $jsonData);
        }

        return $json;
    }

    /**
     * Make a new JSON response model from an entity.
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
     * Make a new JSON response model from an array of entities.
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
     * Get JSON model.
     *
     * @return Json
     */
    protected function getJsonModel(): Json
    {
        return new JsonClass();
    }

    /**
     * Get JSON data model.
     *
     * @return JsonData
     */
    protected function getJsonDataModel(): JsonData
    {
        return new JsonDataClass();
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
