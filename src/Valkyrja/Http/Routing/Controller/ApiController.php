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

namespace Valkyrja\Http\Routing\Controller;

use Throwable;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Contract\Api;
use Valkyrja\Exception\ErrorHandler;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Response\Contract\JsonResponse;

/**
 * Abstract Class ApiController.
 *
 * @author Melech Mizrachi
 */
abstract class ApiController
{
    public function __construct(
        protected Api $api,
        protected ResponseFactory $responseFactory,
    ) {
    }

    /**
     * Create an Api JsonResponse.
     *
     * @param array<array-key, mixed> $data       The json data
     * @param string|null             $message    [optional] The message
     * @param string|null             $status     [optional] The json status
     * @param StatusCode|null         $statusCode [optional] The status code
     * @param string[]|null           $errors     [optional] The errors
     * @param string[]|null           $warnings   [optional] The warnings
     *
     * @return JsonResponse
     */
    public function createApiJsonResponse(
        array $data = [],
        ?string $message = null,
        ?string $status = null,
        ?StatusCode $statusCode = null,
        ?array $errors = null,
        ?array $warnings = null
    ): JsonResponse {
        $json = $this->api->jsonFromArray($data);

        $json->setMessage($message);
        $json->setStatus($status ?? Status::SUCCESS);
        $json->setStatusCode($statusCode ?? StatusCode::OK);
        $json->setErrors($errors ?? []);
        $json->setWarnings($warnings ?? []);

        return $this->responseFactory->createJsonResponse($json->asArray(), $statusCode);
    }

    /**
     * Get an exception response.
     *
     * @param Throwable       $exception  The exception
     * @param string|null     $message    [optional] The message to override
     * @param string|null     $status     [optional] The status
     * @param StatusCode|null $statusCode [optional] The status code
     * @param string[]|null   $errors     [optional] The errors
     * @param string[]|null   $warnings   [optional] The warnings
     *
     * @return JsonResponse
     */
    public function getExceptionResponse(
        Throwable $exception,
        ?string $message = null,
        ?string $status = null,
        ?StatusCode $statusCode = null,
        ?array $errors = null,
        ?array $warnings = null
    ): JsonResponse {
        return $this->createApiJsonResponse(
            [
                'traceCode' => ErrorHandler::getTraceCode($exception),
            ],
            $message ?? $exception->getMessage(),
            $status ?? Status::ERROR,
            $statusCode ?? StatusCode::INTERNAL_SERVER_ERROR,
            $errors,
            $warnings
        );
    }
}
