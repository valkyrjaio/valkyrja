<?php
/*
 * This file is part of the Valkyrja framework.
 *
 * This file serves as an example for a twig extension.
 */
namespace App\View\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Class TwigStaticExtension
 *
 * @package App\View\Extensions
 *
 * @author Melech Mizrachi
 */
class TwigStaticExtension extends Twig_Extension
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Twig_Extension_Static';
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'getConstant', [
                                 $this,
                                 "getConstant",
                             ]
            ),
        ];
    }

    /**
     * Get a global constant in twig template.
     *
     * @param string $name The constant name to get
     *
     * @return mixed|null
     */
    public function constant($name)
    {
        if (defined($name)) {
            return constant($name);
        }

        return null;
    }
}
