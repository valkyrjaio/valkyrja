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

namespace Valkyrja\View\Config;

/**
 * Class Configurations.
 *
 * @author Melech Mizrachi
 */
class Configurations
{
    public function __construct(
        public OrkaConfiguration|null $orka = null,
        public PhpConfiguration|null $php = null,
        public TwigConfiguration|null $twig = null,
    ) {
    }
}
