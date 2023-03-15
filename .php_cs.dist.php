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
            '@PHP80Migration:risky'                    => true,
            '@PHP81Migration'                          => true,
            '@PER'                                     => true,
            '@PER:risky'                               => true,
            '@Symfony'                                 => true,
            '@Symfony:risky'                           => true,
            'no_unused_imports'                        => true,
            'assign_null_coalescing_to_coalesce_equal' => true,
            'combine_consecutive_unsets'               => true,
            'modernize_types_casting'                  => true,
            'no_superfluous_elseif'                    => true,
            'no_superfluous_phpdoc_tags'               => false,
            'no_useless_return'                        => true,
            // 'ordered_interfaces'                       => true,
            'simplified_null_return'                   => true,
            'simple_to_complex_string_variable'        => true,
            'single_line_throw'                        => false,
            'strict_param'                             => true,
            'trailing_comma_in_multiline'              => true,
            'unary_operator_spaces'                    => false,
            'void_return'                              => true,
            'array_syntax'                             => [
                'syntax' => 'short',
            ],
            'binary_operator_spaces'                   => [
                'operators' => [
                    '='  => 'align_single_space',
                    '=>' => 'align_single_space_minimal_by_scope',
                ],
            ],
            'concat_space'                             => [
                'spacing' => 'one',
            ],
            'global_namespace_import'                  => [
                'import_classes'   => true,
                'import_constants' => true,
                'import_functions' => true,
            ],
            'header_comment'                           => [
                'header'       => $header,
                'comment_type' => 'comment',
                'location'     => 'after_declare_strict',
            ],
            'increment_style'                          => [
                'style' => 'post',
            ],
            'ordered_imports'                          => [
                'sort_algorithm' => 'alpha',
                'imports_order'  => ['class', 'function', 'const'],
            ],
            'phpdoc_add_missing_param_annotation'      => [
                'only_untyped' => true,
            ],
            'phpdoc_order'                             => [
                'order' => ['param', 'throws', 'return'],
            ],
            'phpdoc_tag_type'                          => [
                'tags' => ['inheritDoc' => 'annotation'],
            ],
            'phpdoc_to_comment'                        => [
                'ignored_tags' => ['todo', 'var'],
            ],
            'phpdoc_types_order'                       => [
                'sort_algorithm'  => 'none',
                'null_adjustment' => 'always_last',
            ],
            'yoda_style'                               => [
                'equal'            => false,
                'identical'        => false,
                'less_and_greater' => false,
            ],
        ]
    )
    ->setFinder($finder);
