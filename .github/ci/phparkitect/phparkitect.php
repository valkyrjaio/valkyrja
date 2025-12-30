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

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\Extend;
use Arkitect\Expression\ForClasses\HaveAttribute;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\IsAbstract;
use Arkitect\Expression\ForClasses\IsEnum;
use Arkitect\Expression\ForClasses\IsFinal;
use Arkitect\Expression\ForClasses\IsInterface;
use Arkitect\Expression\ForClasses\IsNotAbstract;
use Arkitect\Expression\ForClasses\IsNotEnum;
use Arkitect\Expression\ForClasses\IsNotInterface;
use Arkitect\Expression\ForClasses\IsNotTrait;
use Arkitect\Expression\ForClasses\IsTrait;
use Arkitect\Expression\ForClasses\NotHaveNameMatching;
use Arkitect\Expression\ForClasses\NotResideInTheseNamespaces;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\Rules\Rule;
use Valkyrja\Application\Provider\Provider as ComponentProvider;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Model\Abstract\Model;

return static function (Config $config): void {
    $srcClassSet  = ClassSet::fromDir(__DIR__ . '/../../../src');
    $testClassSet = ClassSet::fromDir(__DIR__ . '/../../../tests');

    $srcRules  = [];
    $testRules = [];

    // $srcRules[] = Rule::allClasses()
    //                   ->that(new ResideInOneOfTheseNamespaces('*'))
    //                   ->should(new ContainDocBlockLike('*@author Melech Mizrachi'))
    //                   ->because('All classes should have an author');

    $srcRules[] = Rule::allClasses()
                      ->that(new HaveAttribute(Attribute::class))
                      ->should(new ResideInOneOfTheseNamespaces('*Attribute\\'))
                      ->because('All attributes should exist in an appropriate namespace');

    // $srcRules[] = Rule::allClasses()
    //                   ->that(new IsAbstract())
    //                   ->andThat(new IsNotInterface())
    //                   ->andThat(new IsNotFinal())
    //                   ->andThat(new IsNotEnum())
    //                   ->andThat(new IsNotTrait())
    //                   ->andThat(new NotHaveNameMatching('*Factory'))
    //                   ->andThat(new NotHaveNameMatching('*Provider'))
    //                   ->andThat(new NotHaveNameMatching('*Security'))
    //                   ->andThat(new NotResideInTheseNamespaces('*Provider'))
    //                   ->should(new HaveNameMatching('*Abstract'))
    //                   ->because('All abstract classes should be properly named');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsFinal())
                      ->andThat(new IsNotEnum())
                      ->andThat(new IsNotInterface())
                      ->andThat(new IsNotAbstract())
                      ->andThat(new IsNotTrait())
                      ->andThat(new NotHaveNameMatching('*Security'))
                      ->andThat(new NotHaveNameMatching('*Provider'))
                      ->should(new ResideInOneOfTheseNamespaces('*Constant\\'))
                      ->because('All final classes are constants and should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new ResideInOneOfTheseNamespaces('*Constant\\'))
                      ->should(new IsFinal())
                      ->because('All constants should be final');

    $srcRules[] = Rule::allClasses()
                      ->that(new Extend(Provider::class))
                      ->should(new HaveNameMatching('*ServiceProvider'))
                      ->because('All service providers should be named appropriately');

    $srcRules[] = Rule::allClasses()
                      ->that(new Extend(Provider::class))
                      ->should(new ResideInOneOfTheseNamespaces('*Provider\\'))
                      ->because('All service providers should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new Extend(ComponentProvider::class))
                      ->should(new HaveNameMatching('*ComponentProvider'))
                      ->because('All component providers should be named appropriately');

    $srcRules[] = Rule::allClasses()
                      ->that(new Extend(ComponentProvider::class))
                      ->should(new ResideInOneOfTheseNamespaces('*Provider\\'))
                      ->because('All component providers should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new HaveNameMatching('*Factory'))
                      ->should(new ResideInOneOfTheseNamespaces('*Factory\\'))
                      ->because('All factories should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new HaveNameMatching('*Command'))
                      ->andThat(new NotResideInTheseNamespaces('*Cli\\Routing\\Attribute\\'))
                      ->andThat(new NotResideInTheseNamespaces('*Cli\\Routing\\Data\\'))
                      ->andThat(new NotHaveNameMatching('*Handler'))
                      ->andThat(new NotHaveNameMatching('*Middleware'))
                      ->should(new ResideInOneOfTheseNamespaces('*Cli\\Command\\'))
                      ->because('All cli commands should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new HaveNameMatching('*Security'))
                      ->should(new ResideInOneOfTheseNamespaces('*Security\\'))
                      ->because('All security classes should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new HaveNameMatching('*Security'))
                      ->should(new IsFinal())
                      ->because('All security classes should be final');

    $srcRules[] = Rule::allClasses()
                      ->that(new HaveNameMatching('*Exception'))
                      ->should(new ResideInOneOfTheseNamespaces('*Exception\\'))
                      ->because('All exceptions should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new Extend(Throwable::class))
                      ->should(new ResideInOneOfTheseNamespaces('*Throwable\\'))
                      ->because('All throwable objects or interfaces should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new Extend(Type::class))
                      ->andThat(new NotResideInTheseNamespaces('*Config'))
                      ->andThat(new NotResideInTheseNamespaces('*Entity'))
                      ->andThat(new NotResideInTheseNamespaces('*Model'))
                      ->should(new ResideInOneOfTheseNamespaces('*Type\\'))
                      ->because('All types should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new Extend(Model::class))
                      ->andThat(new NotResideInTheseNamespaces('*Config'))
                      ->andThat(new NotResideInTheseNamespaces('*Entity'))
                      ->should(new ResideInOneOfTheseNamespaces('*Model\\'))
                      ->because('All models should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new Extend(Entity::class))
                      ->should(new ResideInOneOfTheseNamespaces('*Entity\\'))
                      ->because('All entities should exist in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsInterface())
                      ->should(new ResideInOneOfTheseNamespaces('*Contract\\'))
                      ->because('All interfaces are contracts and should be in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsNotInterface())
                      ->should(new NotResideInTheseNamespaces('*Contract\\'))
                      ->because('All non-interfaces are contracts and should be in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsTrait())
                      ->should(new ResideInOneOfTheseNamespaces('*Trait\\'))
                      ->because('All traits should be in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsNotTrait())
                      ->should(new NotResideInTheseNamespaces('*Trait\\'))
                      ->because('All non-traits should be in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsAbstract())
                      ->andThat(new NotResideInTheseNamespaces('*Factory'))
                      ->andThat(new NotResideInTheseNamespaces('*Provider'))
                      ->andThat(new NotResideInTheseNamespaces('Valkyrja\\Cli\\Routing\\Controller'))
                      ->andThat(new NotResideInTheseNamespaces('Valkyrja\\Http\\Routing\\Controller'))
                      ->should(new ResideInOneOfTheseNamespaces('*Abstract\\'))
                      ->because('All abstract classes should be in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsAbstract())
                      ->should(new NotHaveNameMatching('Abstract*'))
                      ->because('All abstract classes should not be named with abstract naming convention');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsNotAbstract())
                      ->should(new NotHaveNameMatching('Abstract*'))
                      ->because('All classes should not be named with abstract naming convention');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsNotAbstract())
                      ->should(new NotResideInTheseNamespaces('*Abstract\\'))
                      ->because('All non-abstract classes should be in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsEnum())
                      ->should(new ResideInOneOfTheseNamespaces('*Enum\\'))
                      ->because('All enums should be in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsNotEnum())
                      ->should(new NotResideInTheseNamespaces('*Enum\\'))
                      ->because('All non-enums should be in an appropriate namespace');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsEnum())
                      ->should(new NotHaveNameMatching('*Enum*'))
                      ->because('All enums should be appropriately named');

    $srcRules[] = Rule::allClasses()
                      ->that(new IsNotEnum())
                      ->andThat(new NotResideInTheseNamespaces('Valkyrja\\Type\\BuiltIn\\Enum'))
                      ->should(new NotHaveNameMatching('*Enum*'))
                      ->because('All non-enums should not be named with Enum');

    $testRules[] = Rule::allClasses()
                       ->that(new ResideInOneOfTheseNamespaces('*Classes\\'))
                       ->andThat(new NotHaveNameMatching('*Enum'))
                       ->andThat(new IsNotTrait())
                       ->should(new HaveNameMatching('*Class'))
                       ->because('Testable classes should be named appropriately');

    $testRules[] = Rule::allClasses()
                       ->that(new ResideInOneOfTheseNamespaces('*Classes\\'))
                       ->should(new NotHaveNameMatching('*Test'))
                       ->because('Testable classes are not tests');

    $testRules[] = Rule::allClasses()
                       ->that(new NotHaveNameMatching('*Test'))
                       ->should(new NotResideInTheseNamespaces('*Unit\\', '*Functional\\'))
                       ->because('Only tests should be in namespace');

    $testRules[] = Rule::allClasses()
                       ->that(new IsTrait())
                       ->andThat(new NotHaveNameMatching('TestCase'))
                       ->should(new ResideInOneOfTheseNamespaces('*Trait\\'))
                       ->because('All test traits should be in an appropriate namespace');

    $testRules[] = Rule::allClasses()
                       ->that(new IsTrait())
                       ->andThat(new NotHaveNameMatching('TestCase'))
                       ->should(new HaveNameMatching('*Trait'))
                       ->because('All test traits should be named appropriately');

    $config
        ->add($srcClassSet, ...$srcRules);
    $config
        ->add($testClassSet, ...$testRules);
};
