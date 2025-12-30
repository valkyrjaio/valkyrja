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
use Valkyrja\Http\Message\Request\Contract\JsonServerRequest;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Http\Struct\Request\Trait\JsonRequestStruct;
use Valkyrja\Validation\Rule\Is\IsNumeric;
use Valkyrja\Validation\Rule\Is\IsString;
use Valkyrja\Validation\Rule\Is\NotEmpty;
use Valkyrja\Validation\Rule\Is\Required;

/**
 * Struct TestIndexedJsonRequestStruct.
 *
 * @author Melech Mizrachi
 */
enum IndexedJsonRequestStructEnum: int implements RequestStruct
{
    use JsonRequestStruct;

    case first  = 1;
    case second = 2;
    case third  = 3;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getValidationRules(JsonServerRequest|ServerRequest $request): array|null
    {
        self::ensureJsonRequest($request);

        $first  = $request->getParsedJsonParam(self::first->value);
        $second = $request->getParsedJsonParam(self::second->value);
        $third  = $request->getParsedJsonParam(self::third->value);

        return [
            self::first->name  => [
                new Required($first),
                new NotEmpty($first),
            ],
            self::second->name => [
                new IsNumeric($second),
            ],
            self::third->name  => [
                new IsString($third),
            ],
        ];
    }
}
