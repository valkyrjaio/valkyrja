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

namespace Valkyrja\Http\Struct\Request;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Type\BuiltIn\Enum\Arrayable;
use Valkyrja\Validation\Contract\Validate;

/**
 * Trait RequestStruct.
 *
 * @author Melech Mizrachi
 */
trait RequestStruct
{
    use Arrayable;

    /**
     * @inheritDoc
     */
    public static function getDataFromRequest(ServerRequest $request): array
    {
        return static::getOnlyParamsFromRequest($request, ...static::values());
    }

    /**
     * @inheritDoc
     */
    public static function determineIfRequestContainsExtraData(ServerRequest $request): bool
    {
        return ! empty(static::getExceptParamsFromRequest($request, ...static::values()));
    }

    /**
     * @inheritDoc
     */
    public static function getValidationRules(ServerRequest $request): array|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function validate(ServerRequest $request): Validate
    {
        return new \Valkyrja\Validation\Validate(static::getValidationRules($request) ?? []);
    }

    /**
     * Get only the specified request params.
     *
     * @param ServerRequest $request   The request
     * @param string|int    ...$values The values
     *
     * @return array
     */
    abstract protected static function getOnlyParamsFromRequest(ServerRequest $request, string|int ...$values): array;

    /**
     * Get all request params except the ones specified.
     *
     * @param ServerRequest $request   The request
     * @param string|int    ...$values The values
     *
     * @return array
     */
    abstract protected static function getExceptParamsFromRequest(ServerRequest $request, string|int ...$values): array;
}
