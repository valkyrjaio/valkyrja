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
     * @var array|null
     */
    public ?array $data = null;

    /**
     * The errors.
     *
     * @var string[]
     */
    public array $errors = [];

    /**
     * The warnings.
     *
     * @var string[]
     */
    public array $warnings = [];

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
     * Get the errors.
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set the errors.
     *
     * @param string[] $errors The errors
     *
     * @return static
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Set an error.
     *
     * @param string $error The error
     *
     * @return static
     */
    public function setError(string $error): self
    {
        $this->errors[] = $error;

        return $this;
    }
    /**
     * Get the warnings.
     *
     * @return string[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * Set the warnings.
     *
     * @param string[] $warnings The warnings
     *
     * @return static
     */
    public function setWarnings(array $warnings): self
    {
        $this->warnings = $warnings;

        return $this;
    }

    /**
     * Set an warning.
     *
     * @param string $warning The warning
     *
     * @return static
     */
    public function setWarning(string $warning): self
    {
        $this->warnings[] = $warning;

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
