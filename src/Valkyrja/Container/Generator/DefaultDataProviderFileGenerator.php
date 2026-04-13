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

namespace Valkyrja\Container\Generator;

use Override;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Generator\Abstract\ProviderFileGenerator;
use Valkyrja\Container\Provider\ContainerServiceProvider;

class DefaultDataProviderFileGenerator extends ProviderFileGenerator
{
    /**
     * @param non-empty-string $directory The directory
     * @param non-empty-string $namespace The class namespace
     * @param non-empty-string $className The class name
     */
    public function __construct(
        protected string $directory,
        protected string $namespace,
        protected string $className,
    ) {
        parent::__construct(
            directory: $directory,
            namespace: $namespace,
            className: $className,
            serviceClassName: 'ContainerData',
            serviceFullNamespace: ContainerData::class,
            publishMethod: 'publishData',
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function getImports(): string
    {
        $serviceProvider = ContainerServiceProvider::class;

        return <<<PHP
            use $serviceProvider;
            PHP;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function getPublishContents(): string
    {
        return <<<'PHP'
            ContainerServiceProvider::publishData($container);
            PHP;
    }
}
