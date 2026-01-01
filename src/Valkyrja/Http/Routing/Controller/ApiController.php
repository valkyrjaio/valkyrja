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
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Throwable\Handler\WhoopsThrowableHandler;

abstract class ApiController extends Controller
{
    public function __construct(
        protected ApiContract $api,
        ServerRequestContract $request,
        ResponseFactoryContract $responseFactory,
    ) {
        parent::__construct(
            request: $request,
            responseFactory: $responseFactory
        );
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
     * @return JsonResponseContract
     */
    public function createApiJsonResponse(
        array $data = [],
        string|null $message = null,
        string|null $status = null,
        StatusCode|null $statusCode = null,
        array|null $errors = null,
        array|null $warnings = null
    ): JsonResponseContract {
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
     * @return JsonResponseContract
     */
    public function getExceptionResponse(
        Throwable $exception,
        string|null $message = null,
        string|null $status = null,
        StatusCode|null $statusCode = null,
        array|null $errors = null,
        array|null $warnings = null
    ): JsonResponseContract {
        return $this->createApiJsonResponse(
            [
                'traceCode' => WhoopsThrowableHandler::getTraceCode($exception),
            ],
            $message ?? $exception->getMessage(),
            $status ?? Status::ERROR,
            $statusCode ?? StatusCode::INTERNAL_SERVER_ERROR,
            $errors,
            $warnings
        );
    }
}
