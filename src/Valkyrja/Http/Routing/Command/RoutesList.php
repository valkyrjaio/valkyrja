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

namespace Valkyrja\Http\Routing\Command;

use InvalidArgumentException;
use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Enum\FormatForeground;
use Valkyrja\Console\Input\Contract\Input as InputContract;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Output\Contract\Output as OutputContract;
use Valkyrja\Console\Output\Output;
use Valkyrja\Console\Support\Provides;
use Valkyrja\Dispatcher\Data\Contract\ConstantDispatch;
use Valkyrja\Dispatcher\Data\Contract\MethodDispatch;
use Valkyrja\Dispatcher\Data\Contract\PropertyDispatch;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Data\Contract\Route;

use function implode;
use function max;
use function str_repeat;
use function strlen;
use function usort;

/**
 * Class RoutesList.
 *
 * @author Melech Mizrachi
 */
class RoutesList extends Commander
{
    use Provides;

    /** @var string */
    public const string COMMAND = 'routes:list';
    /** @var string */
    public const string PATH = self::COMMAND;
    /** @var string */
    public const string SHORT_DESCRIPTION = 'List all routes';
    /** @var string */
    public const string DESCRIPTION = '';

    /** @var string */
    protected const string INVERT_FORMAT = "\e[7m";
    /** @var string */
    protected const string END_FORMAT = "\e[0m";

    public function __construct(
        protected Collection $collection,
        InputContract $input = new Input(),
        OutputContract $output = new Output()
    ) {
        parent::__construct($input, $output);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     */
    public function run(): int
    {
        $routerRoutes = $this->collection->allFlattened();
        $routes       = [];
        $headerTexts  = [
            'Request Methods',
            'Path',
            'Name',
            'Dispatch',
            'Regex',
        ];
        $lengths      = [
            strlen($headerTexts[0]),
            strlen($headerTexts[1]),
            strlen($headerTexts[2]),
            strlen($headerTexts[3]),
            strlen($headerTexts[4]),
        ];

        // Sort routes by path
        usort($routerRoutes, static fn (Route $a, Route $b) => $a->getPath() <=> $b->getPath());

        foreach ($routerRoutes as $route) {
            $routes[] = $this->setRoute($route, $lengths);
        }

        $sepLine = $this->getSepLine($lengths);
        $odd     = false;

        $this->output->writeMessage($this->oddFormat(true) . $sepLine, true);
        $this->headerMessage($headerTexts, $lengths);
        $this->output->writeMessage($sepLine, true);

        foreach ($routes as $key => $route) {
            $routeMessage = '| '
                . $route[0]
                . str_repeat(' ', $lengths[0] - strlen($route[0]))
                . ' | '
                . $route[1]
                . str_repeat(' ', $lengths[1] - strlen($route[1]))
                . ' | '
                . $route[2]
                . str_repeat(' ', $lengths[2] - strlen($route[2]))
                . ' | '
                . $route[3]
                . str_repeat(' ', $lengths[3] - strlen($route[3]))
                . ' | '
                . $route[4]
                . str_repeat(' ', $lengths[4] - strlen($route[4]))
                . ' |';

            $odd          = $key % 2 > 0;
            $routeMessage = $this->oddFormat($odd) . $routeMessage;

            $this->output->writeMessage($routeMessage . static::END_FORMAT, true);
        }

        $this->output->writeMessage($this->oddFormat(! $odd) . $sepLine . static::END_FORMAT, true);

        return 0;
    }

    /**
     * Set a route as an array from a route object.
     *
     * @param Route           $route   The route object
     * @param array<int, int> $lengths The longest string lengths
     *
     * @throws InvalidArgumentException
     *
     * @return array{0: string, 1: string, 2: string, 3: string, 4: string}
     */
    protected function setRoute(Route $route, array &$lengths): array
    {
        $requestMethod = implode(' | ', array_column($route->getRequestMethods(), 'value'));
        $dispatch      = $route->getDispatch();
        $regex         = $route->getRegex() ?? '';
        $path          = $route->getPath();
        $name          = $route->getName();

        if ($requestMethod === 'HEAD | GET | CONNECT | DELETE | OPTIONS | PATCH | POST | PUT | TRACE') {
            $requestMethod = 'ANY';
        }

        $dispatchString = match (true) {
            $dispatch instanceof ConstantDispatch => ($dispatch->getClass() ?? '') . '::' . $dispatch->getConstant(),
            $dispatch instanceof PropertyDispatch => $dispatch->getClass() . ($dispatch->isStatic() ? '::' : '->') . $dispatch->getProperty(),
            $dispatch instanceof MethodDispatch   => $dispatch->getClass() . ($dispatch->isStatic() ? '::' : '->') . $dispatch->getMethod() . '()',
        };

        $lengths[0] = max($lengths[0], strlen($requestMethod));
        $lengths[1] = max($lengths[1], strlen($path));
        $lengths[2] = max($lengths[2], strlen($name));
        $lengths[3] = max($lengths[3], strlen($dispatchString));
        $lengths[4] = max($lengths[4], strlen($regex));

        return [
            $requestMethod,
            $path,
            $name,
            $dispatchString,
            $regex,
        ];
    }

    /**
     * Get the separation line.
     *
     * @param array<int, int> $lengths The longest lengths
     *
     * @return string
     */
    protected function getSepLine(array $lengths): string
    {
        return '+-' . str_repeat('-', $lengths[0])
            . '-+-' . str_repeat('-', $lengths[1])
            . '-+-' . str_repeat('-', $lengths[2])
            . '-+-' . str_repeat('-', $lengths[3])
            . '-+-' . str_repeat('-', $lengths[4])
            . '-+';
    }

    /**
     * Format odd rows.
     *
     * @param bool $odd
     *
     * @return string
     */
    protected function oddFormat(bool $odd): string
    {
        return $odd
            ? static::INVERT_FORMAT . "\e[" . ((string) FormatForeground::CYAN->value) . 'm'
            : static::INVERT_FORMAT . "\e[" . ((string) FormatForeground::LIGHT_CYAN->value) . 'm';
    }

    /**
     * Output the header message.
     *
     * @param array<int, string> $headerTexts The header texts
     * @param array<int, int>    $lengths     The longest lengths
     *
     * @return void
     */
    protected function headerMessage(array $headerTexts, array $lengths): void
    {
        $headerMessage = '| ' . $headerTexts[0]
            . str_repeat(' ', $lengths[0] - strlen($headerTexts[0]))
            . ' | ' . $headerTexts[1]
            . str_repeat(' ', $lengths[1] - strlen($headerTexts[1]))
            . ' | ' . $headerTexts[2]
            . str_repeat(' ', $lengths[2] - strlen($headerTexts[2]))
            . ' | ' . $headerTexts[3]
            . str_repeat(' ', $lengths[3] - strlen($headerTexts[3]))
            . ' | ' . $headerTexts[4]
            . str_repeat(' ', $lengths[4] - strlen($headerTexts[4]))
            . ' |';

        $this->output->writeMessage($headerMessage, true);
    }
}
