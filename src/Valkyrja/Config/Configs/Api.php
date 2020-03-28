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

namespace Valkyrja\Config\Configs;

use Valkyrja\Api\Enums\ConfigValue;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Model;

use function Valkyrja\env;

/**
 * Class Api.
 *
 * @author Melech Mizrachi
 */
class Api extends Model
{
    /**
     * The JSON model class.
     *
     * @var string
     */
    public string $jsonModel = ConfigValue::JSON_MODEL;

    /**
     * The JSON data model class.
     *
     * @var string
     */
    public string $jsonDataModel = ConfigValue::JSON_DATA_MODEL;

    /**
     * Api constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setJsonModel(ConfigValue::JSON_MODEL);
        $this->setJsonDataModel(ConfigValue::JSON_DATA_MODEL);
    }

    /**
     * Set the JSON model class.
     *
     * @param string $jsonModel [optional] The JSON model
     *
     * @return void
     */
    protected function setJsonModel(string $jsonModel = ConfigValue::JSON_MODEL): void
    {
        $this->jsonModel = (string) env(EnvKey::API_JSON_MODEL, $jsonModel);
    }

    /**
     * Set the JSON data model class.
     *
     * @param string $jsonDataModel [optional] The JSON data model
     *
     * @return void
     */
    protected function setJsonDataModel(string $jsonDataModel = ConfigValue::JSON_DATA_MODEL): void
    {
        $this->jsonDataModel = (string) env(EnvKey::API_JSON_DATA_MODEL, $jsonDataModel);
    }
}
