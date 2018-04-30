<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Commands;

use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Enums\FormatForeground;
use Valkyrja\Console\Enums\FormatOption;
use Valkyrja\Console\Support\ProvidesCommand;
use Valkyrja\Routing\Route;

/**
 * Class RoutesListCommand.
 *
 * @author Melech Mizrachi
 */
class RoutesListCommand extends CommandHandler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'routes:list';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'List all routes';
    public const DESCRIPTION       = '';

    protected const CYAN_FORMAT       = "\e[" . FormatForeground::CYAN . 'm';
    protected const LIGHT_CYAN_FORMAT = "\e[" . FormatForeground::LIGHT_CYAN . 'm';
    protected const INVERT_FORMAT     = "\e[" . FormatOption::INVERSE . 'm';
    protected const END_COLOR_FORMAT  = "\e[" . FormatForeground::DEFAULT . 'm';
    protected const END_FORMAT        = "\e[0m";

    /**
     * Run the command.
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function run(): int
    {
        $routerRoutes = router()->getRoutes();
        $routes       = [];
        $headerTexts  = [
            'Request Methods',
            'Path',
            'Name',
            'Dispatch',
        ];
        $lengths      = [
            \strlen($headerTexts[0]),
            \strlen($headerTexts[1]),
            \strlen($headerTexts[2]),
            \strlen($headerTexts[3]),
        ];

        foreach ($routerRoutes as $route) {
            $this->setRoute($route, $routes, $lengths);
        }

        $sepLine = $this->getSepLine($lengths);
        $odd     = false;

        output()->writeMessage($this->oddFormat(! $odd) . $sepLine, true);
        $this->headerMessage($headerTexts, $lengths);
        output()->writeMessage($sepLine, true);

        foreach ($routes as $key => $route) {
            $routeMessage = '| '
                . $route[0]
                . str_repeat(' ', $lengths[0] - \strlen($route[0]))
                . ' | '
                . $route[1]
                . str_repeat(' ', $lengths[1] - \strlen($route[1]))
                . ' | '
                . $route[2]
                . str_repeat(' ', $lengths[2] - \strlen($route[2]))
                . ' | '
                . $route[3]
                . str_repeat(' ', $lengths[3] - \strlen($route[3]))
                . ' |';

            $odd          = $key % 2 > 0;
            $routeMessage = $this->oddFormat($odd) . $routeMessage;

            output()->writeMessage($routeMessage . static::END_FORMAT, true);
        }

        output()->writeMessage(
            $this->oddFormat(! $odd) . $sepLine . static::END_FORMAT,
            true
        );

        return 0;
    }

    /**
     * Set a route as an array from a route object.
     *
     * @param Route $route   The route object
     * @param array $routes  The flat routes
     * @param array $lengths The longest string lengths
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function setRoute(Route $route, array &$routes, array &$lengths): void
    {
        $requestMethod = implode(' | ', $route->getRequestMethods());
        $dispatch      = 'Closure';

        if (null !== $route->getFunction()) {
            $dispatch = $route->getFunction();
        } elseif (null !== $route->getClass()) {
            $dispatch = $route->getClass()
                . ($route->isStatic() ? '::' : '->')
                . ($route->getMethod()
                    ? $route->getMethod() . '()'
                    : $route->getProperty());
        }

        $lengths[0] = max($lengths[0], \strlen($requestMethod));
        $lengths[1] = max($lengths[1], \strlen($route->getPath()));
        $lengths[2] = max($lengths[2], \strlen($route->getName()));
        $lengths[3] = max($lengths[3], \strlen($dispatch));

        $routes[] = [
            $requestMethod,
            $route->getPath(),
            $route->getName() ?? '',
            $dispatch,
        ];
    }

    /**
     * Get the separation line.
     *
     * @param array $lengths The longest lengths
     *
     * @return string
     */
    protected function getSepLine(array $lengths): string
    {
        return '+-' . str_repeat('-', $lengths[0])
            . '-+-' . str_repeat('-', $lengths[1])
            . '-+-' . str_repeat('-', $lengths[2])
            . '-+-' . str_repeat('-', $lengths[3])
            . '-+';
    }

    /**
     * Output the header message.
     *
     * @param array $headerTexts The header texts
     * @param array $lengths     The longest lengths
     *
     * @return void
     */
    protected function headerMessage(array $headerTexts, array $lengths): void
    {
        $headerMessage = '| ' . $headerTexts[0]
            . str_repeat(' ', $lengths[0] - \strlen($headerTexts[0]))
            . ' | ' . $headerTexts[1]
            . str_repeat(' ', $lengths[1] - \strlen($headerTexts[1]))
            . ' | ' . $headerTexts[2]
            . str_repeat(' ', $lengths[2] - \strlen($headerTexts[2]))
            . ' | ' . $headerTexts[3]
            . str_repeat(' ', $lengths[3] - \strlen($headerTexts[3]))
            . ' |';

        output()->writeMessage($headerMessage, true);
    }

    protected function oddFormat(bool $odd): string
    {
        return $odd
            ? static::INVERT_FORMAT . static::CYAN_FORMAT
            : static::INVERT_FORMAT . static::LIGHT_CYAN_FORMAT;
    }
}
