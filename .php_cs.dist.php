<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$finder = PhpCsFixer\Finder::create()
    ->exclude('.github')
    ->exclude('docs')
    ->exclude('vendor')
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->registerCustomFixers(
        [
        ]
    )
    ->setRules(
        [
            '@PSR1'                       => true,
            '@PSR2'                       => true,
            '@PSR12'                      => true,
            'no_unused_imports'           => true,
            'combine_consecutive_unsets'  => true,
            'trailing_comma_in_multiline' => true,
            'strict_param'                => true,
            'array_syntax'                => [
                'syntax' => 'short',
            ],
            'ordered_imports'             => [
                'sort_algorithm' => 'alpha',
                'imports_order'  => ['class', 'function', 'const'],
            ],
        ]
    )
    ->setFinder($finder);
