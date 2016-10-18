<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\View;

use Twig_Environment;

/**
 * Interface TwigView
 *
 * @package Valkyrja\Contracts\View
 *
 * @author  Melech Mizrachi
 */
interface TwigView extends View
{
    /**
     * Set the twig environment.
     *
     * @param Twig_Environment $twig The twig environment
     *
     * @return void
     */
    public function setTwig(Twig_Environment $twig);
}
