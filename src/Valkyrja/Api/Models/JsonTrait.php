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

namespace Valkyrja\Api\Models;

use Valkyrja\Api\Constants\Status;
use Valkyrja\Api\JsonData;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Support\Model\Traits\ModelTrait;

/**
 * Trait JsonTrait.
 *
 * @author Melech Mizrachi
 */
trait JsonTrait
{
    use ModelTrait;

    /**
     * The message.
     *
     * @var string|null
     */
    public ?string $message = null;

    /**
     * The data.
     *
     * @var JsonData|null
     */
    public ?JsonData $data = null;

    /**
     * The status code.
     *
     * @var int
     */
    public int $statusCode = StatusCode::OK;

    /**
     * The status.
     *
     * @var string
     */
    public string $status = Status::SUCCESS;

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
     * @return static
     */
    public function setMessage(string $message = null): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the data.
     *
     * @return JsonData|null
     */
    public function getData(): ?JsonData
    {
        return $this->data;
    }

    /**
     * Set the data.
     *
     * @param JsonData|null $data
     *
     * @return static
     */
    public function setData(JsonData $data = null): self
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
     * @return static
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
     * @return static
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Serialize properties for json_encode.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $array = [
            'data'       => $this->data,
            'statusCode' => $this->statusCode,
            'status'     => $this->status,
        ];

        if ($this->message) {
            $array['message'] = $this->message;
        }

        return $array;
    }
}
