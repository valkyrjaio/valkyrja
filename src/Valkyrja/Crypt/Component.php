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

namespace Valkyrja\Crypt;

use Valkyrja\Application\Support\Component as AppComponent;

/**
 * Final Class Component.
 *
 * @author Melech Mizrachi
 */
class Component extends AppComponent
{
    /**
     * @inheritDoc
     */
    public static function getName(): string
    {
        return 'crypt';
    }

    /**
     * @inheritDoc
     */
    public static function getConfig(): string
    {
        return Config::class;
    }

    /**
     * @inheritDoc
     */
    public static function getContainerProviders(): array
    {
        return [
            Provider\ServiceProvider::class,
        ];
    }
}
