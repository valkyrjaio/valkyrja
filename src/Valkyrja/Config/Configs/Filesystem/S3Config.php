<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs\Filesystem;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class S3Config.
 *
 * @author Melech Mizrachi
 */
class S3Config extends Model
{
    public string $adapter = CKP::S3;
    public string $dir     = '/';
    public string $key     = '';
    public string $secret  = '';
    public string $region  = '';
    public string $version = '';
    public string $bucket  = '';
    public array  $options = [];

    /**
     * S3Config constructor.
     */
    public function __construct()
    {
        $this->adapter = (string) env(EnvKey::FILESYSTEM_S3_ADAPTER, $this->adapter);
        $this->dir     = (string) env(EnvKey::FILESYSTEM_S3_DIR, storagePath('app'));
        $this->key     = (string) env(EnvKey::FILESYSTEM_S3_KEY, $this->key);
        $this->secret  = (string) env(EnvKey::FILESYSTEM_S3_KEY, $this->secret);
        $this->region  = (string) env(EnvKey::FILESYSTEM_S3_SECRET, $this->region);
        $this->version = (string) env(EnvKey::FILESYSTEM_S3_VERSION, $this->version);
        $this->bucket  = (string) env(EnvKey::FILESYSTEM_S3_BUCKET, $this->bucket);
        $this->options = (array) env(EnvKey::FILESYSTEM_S3_OPTIONS, $this->options);
    }
}
