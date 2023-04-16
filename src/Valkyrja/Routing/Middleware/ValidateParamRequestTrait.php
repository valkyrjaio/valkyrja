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

use Valkyrja\Http\Request;

/**
 * Trait ValidateTypeRequestTrait.
 *
 * @author Melech Mizrachi
 */
trait ValidateParamRequestTrait
{
    /**
     * @inheritDoc
     *
     * @return array<string, array{subject: mixed, rules: array<string, array{arguments: array, message?: string}>}>
     */
    protected static function getRules(Request $request): array
    {
        $rules = [];

        foreach (static::getParamRules() as $param => $paramRule) {
            $rules[$param] = [
                'subject' => static::getParamFromRequest($request, $param),
                'rules'   => $paramRule,
            ];
        }

        return $rules;
    }

    /**
     * Get the param validation rules.
     *
     * <code>
     *      $rules = [
     *          'title' => [
     *              'required' => [
     *                  'arguments'    => [],
     *                  'errorMessage' => 'Title is required.',
     *              ],
     *              'notEmpty' => [
     *                  'arguments'    => [],
     *                  'errorMessage' => 'Title must not be empty.',
     *              ],
     *              'min' => [
     *                  'arguments'    => [20],
     *                  'errorMessage' => 'Title must be at least 20 characters long.',
     *              ],
     *              'max' => [
     *                  'arguments'    => [500],
     *                  'errorMessage' => 'Title must be not be longer than 500 characters.',
     *              ],
     *          ],
     *      ]
     * </code>
     *
     * @return array<string, array<string, array{arguments: array, message?: string}>>
     */
    abstract protected static function getParamRules(): array;

    /**
     * Get a param from the request.
     *
     * @param Request $request The request
     * @param string  $param   The param
     *
     * @return mixed
     */
    abstract protected static function getParamFromRequest(Request $request, string $param): mixed;
}
