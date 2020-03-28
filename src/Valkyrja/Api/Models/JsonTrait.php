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

namespace Valkyrja\Api\Models;

use Valkyrja\Api\Enums\Status;
use Valkyrja\Api\JsonData;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Model\ModelTrait;

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
    protected ?string $message = null;

    /**
     * The data.
     *
     * @var JsonData|null
     */
    protected ?JsonData $data = null;

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
    protected string $status = Status::SUCCESS;

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
     * Get the API model as a JSON response.
     *
     * @return JsonResponse
     */
    public function asResponse(): JsonResponse
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
