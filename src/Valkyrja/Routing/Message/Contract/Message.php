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

namespace Valkyrja\Routing\Message\Contract;

use UnitEnum;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Type\BuiltIn\Enum\Contract\Arrayable;

/**
 * Interface Message.
 *
 * @author Melech Mizrachi
 */
interface Message extends UnitEnum, Arrayable
{
    /**
     * Get the validation rules.
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
     * @return array<string, array<string, array{arguments: array, message?: string}>>|null
     */
    public static function getValidationRules(): array|null;

    /**
     * Get the data for a message from a given request.
     *
     * @param ServerRequest $request The request
     *
     * @return array
     */
    public static function getDataFromRequest(ServerRequest $request): array;

    /**
     * Determine if a request has extra data that was passed that is not defined in the message.
     *
     * @param ServerRequest $request
     *
     * @return bool
     */
    public static function determineIfRequestContainsExtraData(ServerRequest $request): bool;
}
