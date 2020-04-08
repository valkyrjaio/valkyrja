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

namespace Valkyrja\Application\Helpers;

use RuntimeException;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Event;
use Valkyrja\View\View;

use function func_num_args;

/**
 * Trait ApplicationHelpersTrait.
 *
 * @author Melech Mizrachi
 *
 * @property Container $container
 */
trait ApplicationHelpersTrait
{

    /**
     * Offset set.
     *
     * @param string|null $serviceId The service id
     * @param mixed       $service   The service
     *
     * @return void
     */
    public function offsetSet($serviceId, $service): void
    {
        self::$container->bind($serviceId, $service);
    }

    /**
     * Offset exists.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    public function offsetExists($serviceId): bool
    {
        return self::$container->has($serviceId);
    }

    /**
     * Offset unset.
     *
     * @param string $serviceId The service id
     *
     * @return void
     */
    public function offsetUnset($serviceId): void
    {
        throw new RuntimeException('Cannot unset service: ' . $serviceId);
    }

    /**
     * Offset get.
     *
     * @param string $serviceId The service id
     *
     * @return mixed
     */
    public function offsetGet($serviceId)
    {
        if ($serviceId === 'config') {
            return self::$config;
        }

        if ($serviceId === Container::class) {
            return self::$container;
        }

        if ($serviceId === Dispatcher::class) {
            return self::$dispatcher;
        }

        if ($serviceId === Event::class) {
            return self::$events;
        }

        if (self::$container->isSingleton($serviceId)) {
            return self::$container->getSingleton($serviceId);
        }

        return self::$container->get($serviceId);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string|null $template  [optional] The template to use
     * @param array       $variables [optional] The variables to use
     *
     * @return View
     */
    public function view(string $template = null, array $variables = []): View
    {
        /** @var View $view */
        $view = self::$container->getSingleton(View::class);

        if (func_num_args() === 0) {
            return $view;
        }

        return $view->make($template, $variables);
    }
}
