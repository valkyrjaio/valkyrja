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

namespace Valkyrja\Cli\Interaction\Message;

use Override;
use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

use const PHP_VERSION;

class Header extends Message
{
    public function __construct(
        protected string $appName,
        protected string $appVersion,
        RouteContract $route,
        protected string $icon              = ApplicationInfo::ICON,
        protected string $valkyrjaVersion   = ApplicationInfo::VERSION,
        protected string $valkyrjaBuildDate = ApplicationInfo::VERSION_BUILD_DATE_TIME,
        protected string $phpVersion        = PHP_VERSION,
        protected string $projectRoot       = '',
        protected string $actionDescription = '',
        protected string $commandName       = '',
    ) {
        parent::__construct('');

        if ($this->projectRoot === '') {
            $this->projectRoot = (string) getcwd();
        }

        if ($this->actionDescription === '') {
            $this->actionDescription = $route->getDescription();
        }

        if ($this->commandName === '') {
            $this->commandName = $route->getName();
        }
    }

    public function withAppName(string $appName): static
    {
        $new          = clone $this;
        $new->appName = $appName;

        return $new;
    }

    public function withAppVersion(string $appVersion): static
    {
        $new             = clone $this;
        $new->appVersion = $appVersion;

        return $new;
    }

    public function withIcon(string $icon): static
    {
        $new       = clone $this;
        $new->icon = $icon;

        return $new;
    }

    public function withValkyrjaVersion(string $valkyrjaVersion): static
    {
        $new                  = clone $this;
        $new->valkyrjaVersion = $valkyrjaVersion;

        return $new;
    }

    public function withValkyrjaBuildDate(string $valkyrjaBuildDate): static
    {
        $new                    = clone $this;
        $new->valkyrjaBuildDate = $valkyrjaBuildDate;

        return $new;
    }

    public function withPhpVersion(string $phpVersion): static
    {
        $new             = clone $this;
        $new->phpVersion = $phpVersion;

        return $new;
    }

    public function withProjectRoot(string $projectRoot): static
    {
        $new              = clone $this;
        $new->projectRoot = $projectRoot;

        return $new;
    }

    public function withActionDescription(string $actionDescription): static
    {
        $new                    = clone $this;
        $new->actionDescription = $actionDescription;

        return $new;
    }

    public function withCommandName(string $commandName): static
    {
        $new              = clone $this;
        $new->commandName = $commandName;

        return $new;
    }

    #[Override]
    public function getText(): string
    {
        $iconLines = array_map(
            static fn(string $line): string => '│   ' . $line,
            explode("\n", $this->icon)
        );

        return implode("\n", [
            '╭── ' . $this->appName . ' v' . $this->appVersion,
            '│',
            ...$iconLines,
            '│',
            '│   Built on Valkyrja v' . $this->valkyrjaVersion . ' (date: ' . $this->valkyrjaBuildDate . ')',
            '│   Running on PHP ' . $this->phpVersion,
            '│   ' . $this->projectRoot,
            '╰── ' . $this->actionDescription . ' · ' . $this->commandName,
        ]);
    }
}
