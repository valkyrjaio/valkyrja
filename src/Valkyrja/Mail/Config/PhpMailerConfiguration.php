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

namespace Valkyrja\Mail\Config;

use Valkyrja\Mail\Adapter\PHPMailerAdapter;
use Valkyrja\Mail\Constant\ConfigName;

/**
 * Class NullConfiguration.
 *
 * @author Melech Mizrachi
 */
class PhpMailerConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => 'PHPMAILER_NULL_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'PHPMAILER_NULL_DRIVER_CLASS',
    ];

    public function __construct()
    {
        parent::__construct(
            adapterClass: PHPMailerAdapter::class,
        );
    }
}
