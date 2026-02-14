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

namespace Valkyrja\Tests\Classes\Http\Struct;

use Override;
use Valkyrja\Http\Message\Request\Contract\JsonServerRequestContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Request\Trait\JsonRequestStruct;
use Valkyrja\Validation\Rule\Is\IsNumeric;
use Valkyrja\Validation\Rule\Is\IsString;
use Valkyrja\Validation\Rule\Is\NotEmpty;
use Valkyrja\Validation\Rule\Is\Required;

/**
 * Struct TestJsonRequestStruct.
 */
enum JsonRequestStructEnum implements RequestStructContract
{
    use JsonRequestStruct;

    case first;
    case second;
    case third;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getValidationRules(JsonServerRequestContract|ServerRequestContract $request): array|null
    {
        self::ensureJsonRequest($request);

        $parsedJson = $request->getParsedJson();

        $first  = $parsedJson->get(self::first->name);
        $second = $parsedJson->get(self::second->name);
        $third  = $parsedJson->get(self::third->name);

        return [
            self::first->name  => [
                new Required($first),
                new NotEmpty($first),
            ],
            self::second->name => [
                new IsNumeric((int) $second),
            ],
            self::third->name  => [
                new IsString($third),
            ],
        ];
    }
}
