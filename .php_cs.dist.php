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

$header = <<<EOF
This file is part of the Valkyrja Framework package.

(c) Melech Mizrachi <melechmizrachi@gmail.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;


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
            '@PSR1'                               => true,
            '@PSR2'                               => true,
            '@PSR12'                              => true,
            'no_unused_imports'                   => true,
            'combine_consecutive_unsets'          => true,
            'trailing_comma_in_multiline'         => true,
            'strict_param'                        => true,
            'array_syntax'                        => [
                'syntax' => 'short',
            ],
            'concat_space'                        => [
                'spacing' => 'one',
            ],
            'header_comment'                      => [
                'header'       => $header,
                'comment_type' => 'comment',
                'location'     => 'after_declare_strict',
            ],
            'global_namespace_import'             => [
                'import_classes'   => true,
                'import_constants' => true,
                'import_functions' => true,
            ],
            'ordered_imports'                     => [
                'sort_algorithm' => 'alpha',
                'imports_order'  => ['class', 'function', 'const'],
            ],
            'phpdoc_add_missing_param_annotation' => [
                'only_untyped' => true,
            ],
            'phpdoc_types_order'                  => [
                'sort_algorithm'  => 'none',
                'null_adjustment' => 'always_last',
            ],
        ]
    )
    ->setFinder($finder);
