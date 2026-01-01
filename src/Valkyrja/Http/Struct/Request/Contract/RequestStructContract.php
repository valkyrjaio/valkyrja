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

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Struct\Contract\StructContract;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;

/**
 * Interface RequestStructContract.
 */
interface RequestStructContract extends StructContract
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
     * @return array<string, RuleContract[]>|null
     */
    public static function getValidationRules(ServerRequestContract $request): array|null;

    /**
     * Validate the Struct.
     */
    public static function validate(ServerRequestContract $request): ValidatorContract;

    /**
     * Get the data from a given request.
     *
     * @param ServerRequestContract $request The request
     *
     * @return array<array-key, mixed>
     */
    public static function getDataFromRequest(ServerRequestContract $request): array;

    /**
     * Determine if a request has extra data that was passed that is not defined in the struct.
     *
     * @param ServerRequestContract $request
     *
     * @return bool
     */
    public static function determineIfRequestContainsExtraData(ServerRequestContract $request): bool;
}
