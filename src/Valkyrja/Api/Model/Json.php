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

namespace Valkyrja\Api\Model;

use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Model\Contract\Json as Contract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Type\Model\Model;

/**
 * Class Json.
 *
 * @author Melech Mizrachi
 */
class Json extends Model implements Contract
{
    /**
     * The message.
     *
     * @var string|null
     */
    public string|null $message = null;

    /**
     * The data.
     *
     * @var array<string, mixed>|null
     */
    public array|null $data = null;

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
     * @var StatusCode
     */
    public StatusCode $statusCode = StatusCode::OK;

    /**
     * The status.
     *
     * @var string
     */
    public string $status = Status::SUCCESS;

    /**
     * @inheritDoc
     */
    public function getMessage(): string|null
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string|null $message = null): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @inheritDoc
     */
    public function setErrors(array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setError(string $error): static
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * @inheritDoc
     */
    public function setWarnings(array $warnings): static
    {
        $this->warnings = $warnings;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setWarning(string $warning): static
    {
        $this->warnings[] = $warning;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array|null
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData(array|null $data = null): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function setStatusCode(StatusCode $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $array = [
            'data'       => $this->data,
            'statusCode' => $this->statusCode,
            'status'     => $this->status,
        ];

        if ($this->message !== null && $this->message !== '') {
            $array['message'] = $this->message;
        }

        return $array;
    }
}
