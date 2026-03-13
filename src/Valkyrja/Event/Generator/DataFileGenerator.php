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

namespace Valkyrja\Event\Generator;

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Generator\Abstract\ProviderFileGenerator;
use Valkyrja\Event\Data\Contract\ListenerContract;
use Valkyrja\Event\Data\Data;
use Valkyrja\Event\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Event\Provider\ServiceProvider;

use function is_callable;

class DataFileGenerator extends ProviderFileGenerator implements DataFileGeneratorContract
{
    /**
     * @param non-empty-string $directory The directory
     * @param non-empty-string $namespace The class namespace
     * @param non-empty-string $className The class name
     */
    public function __construct(
        protected string $directory,
        protected Data $data,
        protected string $namespace,
        protected string $className,
    ) {
        parent::__construct(
            directory: $directory,
            namespace: $namespace,
            className: $className,
            serviceClassName: 'Data',
            serviceFullNamespace: Data::class,
            publishMethod: 'publishData',
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function generateClassContents(): string
    {
        $dataNamespace = Data::class;

        $events    = var_export($this->data->events, true);
        $listeners = $this->getListenersAsContent();

        // phpcs:disable
        return <<<PHP
            new \\$dataNamespace(
                events: $events,
                listeners: $listeners,
            )
            PHP;
        // phpcs:enable
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function getImports(): string
    {
        $applicationContract = ApplicationContract::class;
        $serviceProvider     = ServiceProvider::class;

        return <<<PHP
            use $applicationContract;
            use $serviceProvider;
            PHP;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function getPublishContents(): string
    {
        $dataContents = $this->generateClassContents();
        $bypassLogic  = $this->getDataBypassLogic();

        return <<<PHP
            $bypassLogic

            \$data = $dataContents;

            \$container->setSingleton(Data::class, \$data);
            PHP;
    }

    /**
     * Bypass logic for the data.
     */
    protected function getDataBypassLogic(): string
    {
        // phpcs:disable
        return <<<'PHP'
            $app = $container->getSingleton(ApplicationContract::class);

            if ($app->getDebugMode()) {
                ServiceProvider::publishData($container);

                return;
            }
            PHP;
        // phpcs:enable
    }

    /**
     * Get all listeners as a string.
     *
     * @return non-empty-string
     */
    protected function getListenersAsContent(): string
    {
        $listeners = $this->data->listeners;

        $listenersContent = '';

        foreach ($listeners as $key => $listener) {
            if (is_callable($listener)) {
                $listener = $listener();
            }

            $listenerContent = $this->getListenerAsContent($listener);

            $listenersContent .= <<<PHP
                '$key' => $listenerContent,

                PHP;
        }

        return <<<PHP
            [
                $listenersContent
            ]
            PHP;
    }

    /**
     * Get the listener as a string.
     *
     * @return non-empty-string
     */
    protected function getListenerAsContent(ListenerContract $listener): string
    {
        $contract = ListenerContract::class;
        $content  = $this->generateObjectsContents($listener);

        // phpcs:disable
        return <<<PHP
            static fn (): \\$contract => $content
            PHP;
        // phpcs:enable
    }
}
