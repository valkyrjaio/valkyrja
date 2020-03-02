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

namespace Valkyrja\Filesystem;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * Abstract Class FlysystemS3.
 *
 * @author Melech Mizrachi
 */
class FlysystemS3 extends FlysystemAdapter
{
    /**
     * Make a new adapter instance.
     *
     * @return static
     */
    public static function make(): self
    {
        $config       = config()['filesystem']['disks']['s3'];
        $clientConfig = [
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret'],
            ],
            'region'      => $config['region'],
            'version'     => $config['version'],
        ];

        return new static(
            new AwsS3Adapter(
                new S3Client($clientConfig), $config['bucket'], $config['dir']
            )
        );
    }
}
