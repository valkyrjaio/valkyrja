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

use Rector\CodingStyle\Rector\Stmt\RemoveUselessAliasInUseStatementRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

$rector = RectorConfig::configure();

return $rector
    ->withParallel()
    ->withImportNames(removeUnusedImports: true)
    ->withAutoloadPaths([
        __DIR__ . '/../../../vendor/autoload.php',
    ])
    ->withPaths([
        __DIR__ . '/../../../functions',
        __DIR__ . '/../../../src',
        __DIR__ . '/../../../tests',
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        AddOverrideAttributeToOverriddenMethodsRector::class,
        ExplicitNullableParamTypeRector::class,
        NewMethodCallWithoutParenthesesRector::class,
        RemoveUselessAliasInUseStatementRector::class,
        SeparateMultiUseImportsRector::class,
    ]);
