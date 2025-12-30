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
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Http\Struct\Request\Trait\QueryRequestStruct;
use Valkyrja\Validation\Rule\Is\IsNumeric;
use Valkyrja\Validation\Rule\Is\IsString;
use Valkyrja\Validation\Rule\Is\NotEmpty;
use Valkyrja\Validation\Rule\Is\Required;

/**
 * Struct TestIndexedQueryRequestStruct.
 *
 * @author Melech Mizrachi
 */
enum IndexedQueryRequestStructEnum: int implements RequestStruct
{
    use QueryRequestStruct;

    case first  = 1;
    case second = 2;
    case third  = 3;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getValidationRules(ServerRequest $request): array|null
    {
        $first  = $request->getParsedBodyParam(self::first->value);
        $second = $request->getParsedBodyParam(self::second->value);
        $third  = $request->getParsedBodyParam(self::third->value);

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
