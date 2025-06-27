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

namespace Valkyrja\Api;

use Valkyrja\Api\Constant\ConfigName;
use Valkyrja\Api\Constant\EnvName;
use Valkyrja\Api\Model\Contract\Json as JsonContract;
use Valkyrja\Api\Model\Contract\JsonData as JsonDataContract;
use Valkyrja\Api\Model\Json;
use Valkyrja\Api\Model\JsonData;
use Valkyrja\Support\Config as ParentConfig;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::JSON_MODEL => EnvName::JSON_MODEL,
        ConfigName::DATA_MODEL => EnvName::DATA_MODEL,
    ];

    /**
     * @param class-string<JsonContract>     $jsonModel The JSON model
     * @param class-string<JsonDataContract> $dataModel The data model
     */
    public function __construct(
        public string $jsonModel = Json::class,
        public string $dataModel = JsonData::class,
    ) {
    }
}
