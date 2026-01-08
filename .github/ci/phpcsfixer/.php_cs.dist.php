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
                           ->in(__DIR__ . '/../../../');

return new PhpCsFixer\Config()
    ->setParallelConfig(
        new PhpCsFixer\Runner\Parallel\ParallelConfig(
            maxProcesses: 5,
            filesPerProcess: 10,
            processTimeout: 240
        )
    )
    ->setRiskyAllowed(true)
    ->registerCustomFixers(
        [
        ]
    )
    ->setRules(
        [
            '@PHP80Migration:risky'                    => true,
            '@PHP81Migration'                          => true,
            '@PER-CS'                                  => true,
            '@PER-CS:risky'                            => true,
            '@Symfony'                                 => true,
            '@Symfony:risky'                           => true,
            'no_unused_imports'                        => true,
            'align_multiline_comment'                  => true,
            'array_indentation'                        => true,
            'assign_null_coalescing_to_coalesce_equal' => true,
            'combine_consecutive_issets'               => true,
            'combine_consecutive_unsets'               => true,
            'comment_to_phpdoc'                        => true,
            // 'explicit_string_variable'                 => true,
            'modernize_types_casting'                  => true,
            'no_unreachable_default_argument_value'    => true,
            'no_superfluous_elseif'                    => true,
            'no_superfluous_phpdoc_tags'               => false,
            'no_useless_else'                          => true,
            'no_useless_return'                        => true,
            // 'ordered_interfaces'                       => true,
            'phpdoc_var_annotation_correct_order'      => true,
            'php_unit_strict'                          => true,
            // 'return_assignment'                        => true,
            'simplified_null_return'                   => true,
            'simple_to_complex_string_variable'        => true,
            'single_line_throw'                        => false,
            'static_lambda'                            => true,
            'strict_comparison'                        => true,
            'strict_param'                             => true,
            'trailing_comma_in_multiline'              => true,
            'unary_operator_spaces'                    => false,
            'void_return'                              => true,
            'array_syntax'                             => [
                'syntax' => 'short',
            ],
            'blank_line_before_statement'              => [
                'statements' => [
                    'break',
                    // 'case',
                    'continue',
                    'declare',
                    'default',
                    'do',
                    'exit',
                    'for',
                    'foreach',
                    'goto',
                    'if',
                    'include',
                    'include_once',
                    // 'phpdoc',
                    'require',
                    'require_once',
                    'return',
                    'switch',
                    'throw',
                    'try',
                    'while',
                    'yield',
                    'yield_from',
                ],
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
            'method_argument_space'                    => [
                'keep_multiple_spaces_after_comma' => false,
                'on_multiline'                     => 'ensure_fully_multiline',
                'after_heredoc'                    => true,
            ],
            'multiline_whitespace_before_semicolons'   => [
                'strategy' => 'no_multi_line',
            ],
            'nullable_type_declaration'                => [
                'syntax' => 'union',
            ],
            'no_alias_functions'                       => [
                'sets' => ['@all'],
            ],
            'operator_linebreak'                       => [
                'only_booleans' => false,
                'position'      => 'beginning',
            ],
            'ordered_class_elements'                   => [
                'order'          => [
                    'use_trait',
                    'case',
                    'constant_public',
                    'constant_protected',
                    'constant_private',
                    'property_public_static',
                    'property_protected_static',
                    'property_private_static',
                    'property_public_readonly',
                    'property_protected_readonly',
                    'property_private_readonly',
                    'property_public',
                    'property_protected',
                    'property_private',
                    'construct',
                    'destruct',
                    'method_public_static',
                    'method_public_abstract_static',
                    'method_protected_static',
                    'method_protected_abstract_static',
                    'method_private_static',
                    'method_private_abstract_static',
                    'phpunit',
                    'method_public',
                    // 'magic',
                    'method_public_abstract',
                    'method_protected',
                    'method_protected_abstract',
                    'method_private',
                    'method_private_abstract',
                ],
                'sort_algorithm' => 'none',
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
                'ignored_tags' => ['todo', 'var', 'psalm-suppress'],
            ],
            'phpdoc_types_order'                       => [
                'sort_algorithm'  => 'none',
                'null_adjustment' => 'always_last',
            ],
            'php_unit_test_case_static_method_calls'   => [
                'call_type' => 'self',
                'methods'   => [
                    'any'   => 'this',
                    'once'  => 'this',
                    'never' => 'this',
                ],
            ],
            'yoda_style'                               => [
                'equal'            => false,
                'identical'        => false,
                'less_and_greater' => false,
            ],
        ]
    )
    ->setFinder($finder);
