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

namespace Valkyrja\Http\Models;

use Exception;
use Valkyrja\Http\ApiModel as Contract;
use Valkyrja\Http\Enums\ApiStatus;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Model\ModelTrait;
use Valkyrja\ORM\Entity;

use function app;
use function end;
use function get_class;
use function json_encode;
use function strtolower;

use const JSON_THROW_ON_ERROR;

/**
 * Class ApiModel.
 *
 * @author Melech Mizrachi
 */
class ApiModel implements Contract
{
    use ModelTrait;

    /**
     * The item.
     *
     * @var object|null
     */
    protected ?object $item = null;

    /**
     * The item key.
     *
     * @var string
     */
    protected string $itemKey = 'item';

    /**
     * The items.
     *
     * @var object[]|null
     */
    protected ?array $items = null;

    /**
     * The items key
     *
     * @var string
     */
    protected string $itemsKey = 'items';

    /**
     * The total.
     *
     * @var int|null
     */
    protected ?int $total = null;

    /**
     * The message.
     *
     * @var string|null
     */
    protected ?string $message = null;

    /**
     * The messages.
     *
     * @var array|null
     */
    protected ?array $messages = null;

    /**
     * The data.
     *
     * @var array|null
     */
    protected ?array $data = null;

    /**
     * The status code.
     *
     * @var int
     */
    protected int $statusCode = StatusCode::OK;

    /**
     * The status.
     *
     * @var string
     */
    protected string $status = ApiStatus::SUCCESS;

    /**
     * Make a new API model from an exception.
     *
     * @param Exception $exception
     *
     * @return static
     */
    public static function fromException(Exception $exception): self
    {
        $apiModel = new static();

        $apiModel->data = [
            'code' => $exception->getCode(),
        ];

        $apiModel->message    = $exception->getMessage();
        $apiModel->status     = ApiStatus::ERROR;
        $apiModel->statusCode = StatusCode::INTERNAL_SERVER_ERROR;

        if (app()->debug()) {
            $apiModel->data['file']  = $exception->getFile();
            $apiModel->data['line']  = $exception->getLine();
            $apiModel->data['trace'] = $exception->getTrace();
        }

        if ($exception instanceof HttpException) {
            $apiModel->setStatusCode($exception->getStatusCode());
        }

        return $apiModel;
    }

    /**
     * Make a new API model from an entity.
     *
     * @param Entity $entity
     *
     * @return static
     */
    public static function fromEntity(Entity $entity): self
    {
        $apiModel = new static();

        $apiModel->item = $entity;

        $apiModel->setItemKeysFromEntity($entity);

        return $apiModel;
    }

    /**
     * Make a new API model from an array of entities.
     *
     * @param Entity ...$entities
     *
     * @return static
     */
    public static function fromEntities(Entity ...$entities): self
    {
        $apiModel = new static();

        $apiModel->items = $entities;

        if ($entities) {
            $apiModel->setItemKeysFromEntity($entities[0]);
        }

        return $apiModel;
    }

    /**
     * Get the item.
     *
     * @return object|null
     */
    public function getItem(): ?object
    {
        return $this->item;
    }

    /**
     * Set the item.
     *
     * @param object|null $item
     *
     * @return $this
     */
    public function setItem(object $item = null): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get the item key.
     *
     * @return string
     */
    public function getItemKey(): string
    {
        return $this->itemKey;
    }

    /**
     * Set the item key.
     *
     * @param string $itemKey
     *
     * @return $this
     */
    public function setItemKey(string $itemKey): self
    {
        $this->itemKey = $itemKey;

        return $this;
    }

    /**
     * Get the items.
     *
     * @return array|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * Set the items.
     *
     * @param array|null $items
     *
     * @return $this
     */
    public function setItems(array $items = null): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get the items key.
     *
     * @return string
     */
    public function getItemsKey(): string
    {
        return $this->itemsKey;
    }

    /**
     * Set the items key.
     *
     * @param string $itemsKey
     *
     * @return $this
     */
    public function setItemsKey(string $itemsKey): self
    {
        $this->itemsKey = $itemsKey;

        return $this;
    }

    /**
     * Get the total.
     *
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    /**
     * Set the total.
     *
     * @param int|null $total
     *
     * @return $this
     */
    public function setTotal(int $total = null): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the message.
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set the error message.
     *
     * @param string|null $message
     *
     * @return $this
     */
    public function setMessage(string $message = null): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the messages.
     *
     * @return array|null
     */
    public function getMessages(): ?array
    {
        return $this->messages;
    }

    /**
     * Set the messages.
     *
     * @param array|null $messages
     *
     * @return $this
     */
    public function setMessages(array $messages = null): self
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get the data.
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Set the data.
     *
     * @param array|null $data
     *
     * @return static
     */
    public function setData(array $data = null): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set the status code.
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get the status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the status.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the API model as a JSON string.
     *
     * @return string
     */
    public function asJson(): string
    {
        return json_encode($this->asArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Get the API model as a JSON response.
     *
     * @return JsonResponse
     */
    public function asJsonResponse(): JsonResponse
    {
        return json($this->asArray(), $this->statusCode);
    }

    /**
     * Serialize properties for json_encode.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $array = [
            'data'       => $this->getDataForJsonSerialize(),
            'statusCode' => $this->statusCode,
            'status'     => $this->status,
        ];

        if ($this->message) {
            $array['message'] = $this->message;
        }

        return $array;
    }

    /**
     * Get data for json serialize.
     *
     * @return array|null
     */
    protected function getDataForJsonSerialize(): ?array
    {
        $data = $this->data ?? [];

        if ($this->messages) {
            $data['messages'] = $this->messages;
        }

        if ($this->item) {
            $data[$this->itemKey] = $this->item;
        }

        if ($this->items) {
            $data[$this->itemsKey] = $this->items;
        }

        if ($this->total) {
            $data['total'] = $this->total;
        }

        return empty($data) ? null : $data;
    }

    /**
     * Set item keys from entity.
     *
     * @param Entity $entity
     *
     * @return void
     */
    protected function setItemKeysFromEntity(Entity $entity): void
    {
        $classNameParts = explode('\\', get_class($entity));
        $className      = strtolower(end($classNameParts));

        $this->itemKey  = $className;
        $this->itemsKey = $className . 's';
    }
}
