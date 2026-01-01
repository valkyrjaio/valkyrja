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

namespace Valkyrja\Http\Struct\Request\Trait;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Type\BuiltIn\Enum\Trait\Arrayable;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;
use Valkyrja\Validation\Validator\Validator;

/**
 * Trait RequestStruct.
 */
trait RequestStruct
{
    use Arrayable;

    /**
     * @inheritDoc
     */
    public static function getDataFromRequest(ServerRequestContract $request): array
    {
        return static::getOnlyParamsFromRequest($request, ...static::values());
    }

    /**
     * @inheritDoc
     */
    public static function determineIfRequestContainsExtraData(ServerRequestContract $request): bool
    {
        return ! empty(static::getExceptParamsFromRequest($request, ...static::values()));
    }

    /**
     * @inheritDoc
     */
    public static function getValidationRules(ServerRequestContract $request): array|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function validate(ServerRequestContract $request): ValidatorContract
    {
        return new Validator(static::getValidationRules($request) ?? []);
    }

    /**
     * Get only the specified request params.
     *
     * @param ServerRequestContract $request   The request
     * @param string|int            ...$values The values
     *
     * @return array
     */
    abstract protected static function getOnlyParamsFromRequest(ServerRequestContract $request, string|int ...$values): array;

    /**
     * Get all request params except the ones specified.
     *
     * @param ServerRequestContract $request   The request
     * @param string|int            ...$values The values
     *
     * @return array
     */
    abstract protected static function getExceptParamsFromRequest(ServerRequestContract $request, string|int ...$values): array;
}
