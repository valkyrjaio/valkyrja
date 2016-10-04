<?php

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

abstract class Twig_Extension implements Twig_ExtensionInterface
{
    /**
     * {@inheritdoc}
     *
     * @deprecated since 1.23 (to be removed in 2.0), implement Twig_Extension_InitRuntimeInterface instead
     */
    public function initRuntime(Twig_Environment $environment)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated since 1.23 (to be removed in 2.0), implement Twig_Extension_GlobalsInterface instead
     */
    public function getGlobals()
    {
        return [];
    }
}
