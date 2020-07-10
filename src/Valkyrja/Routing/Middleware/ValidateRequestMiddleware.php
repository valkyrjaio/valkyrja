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

namespace Valkyrja\Routing\Middleware;

use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Support\Middleware;
use Valkyrja\Validation\Validator;

/**
 * Class ValidateRequestMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class ValidateRequestMiddleware extends Middleware
{
    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param Request $request The request
     *
     * @return Request|Response
     */
    public static function before(Request $request)
    {
        $container = self::$container;
        /** @var Validator $validator */
        $validator = $container->getSingleton(Validator::class);
        $validator->setRules(static::rules($request));

        if (! $validator->validate()) {
            /** @var ResponseFactory $responseFactory */
            $responseFactory = $container->getSingleton(ResponseFactory::class);

            return $responseFactory->createResponse($validator->getErrorMessage(), StatusCode::BAD_REQUEST);
        }

        return $request;
    }

    /**
     * Get the rules.
     *
     * <code>
     *      $rules = [
     *          'title' => [
     *              'subject' => $request->getParsedBody()['title'] ?? null,
     *              'rules' => [
     *                  'required' => [
     *                      'arguments'    => [],
     *                      'errorMessage' => 'Title is required.',
     *                  ],
     *                  'notEmpty' => [
     *                      'arguments'    => [],
     *                      'errorMessage' => 'Title must not be empty.',
     *                  ],
     *                  'min' => [
     *                      'arguments'    => [20],
     *                      'errorMessage' => 'Title must be at least 20 characters long.',
     *                  ],
     *                  'max' => [
     *                      'arguments'    => [500],
     *                      'errorMessage' => 'Title must be not be longer than 500 characters.',
     *                  ],
     *              ]
     *          ],
     *      ]
     * </code>
     *
     * @param Request $request The request
     *
     * @return array
     */
    abstract protected static function rules(Request $request): array;
}
