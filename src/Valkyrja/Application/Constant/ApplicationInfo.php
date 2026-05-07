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

namespace Valkyrja\Application\Constant;

final class ApplicationInfo
{
    /**
     * The Application framework version.
     *
     * @var non-empty-string
     */
    public const string VERSION = '26.1.0';

    /**
     * The Application framework version build datetime.
     *
     * @var non-empty-string
     */
    public const string VERSION_BUILD_DATE_TIME = 'May 5 2026 16:12:18 MST';

    /**
     * The valkyrja framework ascii art.
     *
     * @var non-empty-string
     */
    public const string ASCII = <<<'TEXT'
                     _ _               _
         /\   /\__ _| | | ___   _ _ __(_) __ _
         \ \ / / _` | | |/ / | | | '__| |/ _` |
          \ V / (_| | |   <| |_| | |  | | (_| |
           \_/ \__,_|_|_|\_\\__, |_| _/ |\__,_|
                            |___/   |__/
        TEXT;

    /**
     * The default CLI banner icon (Valkyrie).
     *
     * @var non-empty-string
     */
    public const string ICON = <<<'ICON'
        ▗▄▄▖     ▗▄▄▖
        ▝▜██▄▄▄▄▄██▛▘
           ▝▜███▛▘
              █
        ICON;
}
