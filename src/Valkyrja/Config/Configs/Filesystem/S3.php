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
use Valkyrja\Config\Models\Model;

use function env;

/**
 * Class S3.
 *
 * @author Melech Mizrachi
 */
class S3 extends Model
{
    /**
     * The adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The dir.
     *
     * @var string
     */
    public string $dir;

    /**
     * The key.
     *
     * @var string
     */
    public string $key;

    /**
     * The secret.
     *
     * @var string
     */
    public string $secret;

    /**
     * The region.
     *
     * @var string
     */
    public string $region;

    /**
     * The version.
     *
     * @var string
     */
    public string $version;

    /**
     * The bucket.
     *
     * @var string
     */
    public string $bucket;

    /**
     * The options.
     *
     * @var array
     */
    public array $options;

    /**
     * S3 constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setAdapter();
        $this->setDir();
        $this->setKey();
        $this->setSecret();
        $this->setRegion();
        $this->setVersion();
        $this->setBucket();
        $this->setOptions();
    }

    /**
     * Set the adapter.
     *
     * @param string $adapter [optional] The adapter
     *
     * @return void
     */
    protected function setAdapter(string $adapter = CKP::S3): void
    {
        $this->adapter = (string) env(EnvKey::FILESYSTEM_S3_ADAPTER, $adapter);
    }

    /**
     * Set the dir.
     *
     * @param string $dir [optional] The dir.
     *
     * @return void
     */
    protected function setDir(string $dir = '/'): void
    {
        $this->dir = (string) env(EnvKey::FILESYSTEM_S3_DIR, $dir);
    }

    /**
     * Set the key.
     *
     * @param string $key [optional] The key
     *
     * @return void
     */
    protected function setKey(string $key = ''): void
    {
        $this->key = (string) env(EnvKey::FILESYSTEM_S3_KEY, $key);
    }

    /**
     * Set the secret.
     *
     * @param string $secret [optional] The secret
     *
     * @return void
     */
    protected function setSecret(string $secret = ''): void
    {
        $this->secret = (string) env(EnvKey::FILESYSTEM_S3_KEY, $secret);
    }

    /**
     * Set the region.
     *
     * @param string $region [optional] The region
     *
     * @return void
     */
    protected function setRegion(string $region = ''): void
    {
        $this->region = (string) env(EnvKey::FILESYSTEM_S3_SECRET, $region);
    }

    /**
     * Set the version.
     *
     * @param string $version [optional] The version
     *
     * @return void
     */
    protected function setVersion(string $version = ''): void
    {
        $this->version = (string) env(EnvKey::FILESYSTEM_S3_VERSION, $version);
    }

    /**
     * Set the bucket.
     *
     * @param string $bucket [optional] The bucket
     *
     * @return void
     */
    protected function setBucket(string $bucket = ''): void
    {
        $this->bucket = (string) env(EnvKey::FILESYSTEM_S3_BUCKET, $bucket);
    }

    /**
     * Set the options.
     *
     * @param array $options [optional] The options
     *
     * @return void
     */
    protected function setOptions(array $options = []): void
    {
        $this->options = (array) env(EnvKey::FILESYSTEM_S3_OPTIONS, $options);
    }
}
