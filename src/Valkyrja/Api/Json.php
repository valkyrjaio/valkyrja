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

use Valkyrja\Http\JsonResponse;
use Valkyrja\Model\Model;

/**
 * Interface Json.
 *
 * @author Melech Mizrachi
 */
interface Json extends Model
{
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
     * Get the data.
     *
     * @return JsonData|null
     */
    public function getData(): ?JsonData;

    /**
     * Set the data.
     *
     * @param JsonData|null $data
     *
     * @return $this
     */
    public function setData(JsonData $data = null): self;

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
     * Get the API model as a JSON response.
     *
     * @return JsonResponse
     */
    public function asResponse(): JsonResponse;
}
