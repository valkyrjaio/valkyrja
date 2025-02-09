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

namespace Valkyrja\Http\Struct\Request\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Struct\Contract\Struct;
use Valkyrja\Validation\Contract\Validate;
use Valkyrja\Validation\Rule\Contract\Rule;

/**
 * Interface RequestStruct.
 *
 * @author Melech Mizrachi
 */
interface RequestStruct extends Struct
{
    /**
     * Get the validation rules.
     *
     * <code>
     *      return [
     *          self::title->name => [
     *              new Required($title = $request->getParsedBodyParam(self::title->name)),
     *              new NotEmpty($title),
     *          ],
     *      ]
     * </code>
     *
     * @return array<string, Rule[]>|null
     */
    public static function getValidationRules(ServerRequest $request): ?array;

    /**
     * Validate the Struct.
     */
    public static function validate(ServerRequest $request): Validate;

    /**
     * Get the data from a given request.
     *
     * @param ServerRequest $request The request
     *
     * @return array<array-key, mixed>
     */
    public static function getDataFromRequest(ServerRequest $request): array;

    /**
     * Determine if a request has extra data that was passed that is not defined in the struct.
     *
     * @param ServerRequest $request
     *
     * @return bool
     */
    public static function determineIfRequestContainsExtraData(ServerRequest $request): bool;
}
