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

namespace Valkyrja\Routing\Support;

use Valkyrja\Api\Api;
use Valkyrja\Api\Constants\Status;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\JsonResponse;

/**
 * Abstract Class ApiController.
 *
 * @author Melech Mizrachi
 */
abstract class ApiController extends Controller
{
    /**
     * The Api service.
     *
     * @var Api
     */
    private static Api $api;

    /**
     * Get the Api service.
     *
     * @return Api
     */
    protected static function getApi(): Api
    {
        return self::$api ?? self::$api = self::$container->getSingleton(Api::class);
    }

    /**
     * Create an Api JsonResponse.
     *
     * @param array       $data       The json data
     * @param string|null $message    [optional] The message
     * @param string|null $status     [optional] The json status
     * @param int|null    $statusCode [optional] The status code
     *
     * @return JsonResponse
     */
    protected static function createApiJsonResponse(
        array $data,
        string $message = null,
        string $status = null,
        int $statusCode = null
    ): JsonResponse {
        $json = self::getApi()->jsonFromArray($data);

        $json->setMessage($message);
        $json->setStatus($status ?? Status::SUCCESS);
        $json->setStatusCode($statusCode ?? StatusCode::OK);

        return self::$responseFactory->createJsonResponse($json->__toArray(), $statusCode);
    }
}
