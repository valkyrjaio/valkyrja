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

namespace Valkyrja\Tests\Classes\Container\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

/**
 * Testable Provider/Provides Trait class.
 */
final class ProvidesClass implements ServiceProviderContract
{
    public static function publish(ContainerContract $container): void
    {
    }

    #[Override]
    public function publishers(): array
    {
        return [];
    }
}
