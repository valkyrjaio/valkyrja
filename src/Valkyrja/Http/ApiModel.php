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

namespace Valkyrja\Http;

use Exception;
use Valkyrja\Model\Model;
use Valkyrja\ORM\Entity;

/**
 * Interface ApiModel.
 *
 * @author Melech Mizrachi
 */
interface ApiModel extends Model
{
    /**
     * Make a new API model from an exception.
     *
     * @param Exception $exception
     *
     * @return static
     */
    public static function fromException(Exception $exception): self;

    /**
     * Make a new API model from an entity.
     *
     * @param Entity $entity
     *
     * @return static
     */
    public static function fromEntity(Entity $entity): self;

    /**
     * Make a new API model from an array of entities.
     *
     * @param Entity ...$entities
     *
     * @return static
     */
    public static function fromEntities(Entity ...$entities): self;

    /**
     * Get the item.
     *
     * @return object|null
     */
    public function getItem(): ?object;

    /**
     * Set the item.
     *
     * @param object|null $item
     *
     * @return $this
     */
    public function setItem(object $item = null): self;

    /**
     * Get the item key.
     *
     * @return string
     */
    public function getItemKey(): string;

    /**
     * Set the item key.
     *
     * @param string $itemKey
     *
     * @return $this
     */
    public function setItemKey(string $itemKey): self;

    /**
     * Get the items.
     *
     * @return array|null
     */
    public function getItems(): ?array;

    /**
     * Set the items.
     *
     * @param array|null $items
     *
     * @return $this
     */
    public function setItems(array $items = null): self;

    /**
     * Get the items key.
     *
     * @return string
     */
    public function getItemsKey(): string;

    /**
     * Set the items key.
     *
     * @param string $itemsKey
     *
     * @return $this
     */
    public function setItemsKey(string $itemsKey): self;

    /**
     * Get the total.
     *
     * @return int|null
     */
    public function getTotal(): ?int;

    /**
     * Set the total.
     *
     * @param int|null $total
     *
     * @return $this
     */
    public function setTotal(int $total = null): self;

    /**
     * Get the message.
     *
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * Set the error message.
     *
     * @param string|null $message
     *
     * @return $this
     */
    public function setMessage(string $message = null): self;

    /**
     * Get the messages.
     *
     * @return array|null
     */
    public function getMessages(): ?array;

    /**
     * Set the messages.
     *
     * @param array|null $messages
     *
     * @return $this
     */
    public function setMessages(array $messages = null): self;

    /**
     * Get the data.
     *
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * Set the data.
     *
     * @param array|null $data
     *
     * @return $this
     */
    public function setData(array $data = null): self;

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Set the status code.
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode(int $statusCode): self;

    /**
     * Get the status.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set the status.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * Get the API model as a JSON string.
     *
     * @return string
     */
    public function asJson(): string;

    /**
     * Get the API model as a JSON response.
     *
     * @return JsonResponse
     */
    public function asJsonResponse(): JsonResponse;
}
