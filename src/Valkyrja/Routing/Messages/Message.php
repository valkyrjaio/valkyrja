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

namespace Valkyrja\Routing\Messages;

use Valkyrja\Http\Request;
use Valkyrja\Type\Types\Enum\Arrayable;

/**
 * Trait Message.
 *
 * @author Melech Mizrachi
 */
trait Message
{
    use Arrayable;

    /**
     * @inheritDoc
     */
    public static function getDataFromRequest(Request $request): array
    {
        return static::getOnlyParamsFromRequest($request, ...static::values());
    }

    /**
     * @inheritDoc
     */
    public static function determineIfRequestContainsExtraData(Request $request): bool
    {
        return ! empty(static::getExceptParamsFromRequest($request, ...static::values()));
    }

    /**
     * @inheritDoc
     */
    public static function getValidationRules(): array|null
    {
        return null;
    }

    /**
     * Get only the specified request params.
     *
     * @param Request    $request   The request
     * @param int|string ...$values The values
     *
     * @return array
     */
    abstract protected static function getOnlyParamsFromRequest(Request $request, int|string ...$values): array;

    /**
     * Get all request params except the ones specified.
     *
     * @param Request    $request   The request
     * @param int|string ...$values The values
     *
     * @return array
     */
    abstract protected static function getExceptParamsFromRequest(Request $request, int|string ...$values): array;
}
