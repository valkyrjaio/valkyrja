# Release Notes for 26.x

## [Unreleased](https://github.com/valkyrjaio/valkyrja/compare/v26.2.0...26.x)

## [v26.2.0](https://github.com/valkyrjaio/valkyrja/compare/v26.1.0...v26.2.0) - 2026-05-07

* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/793
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/794
* [GitHub] Update ci-phpcodesniffer-php workflow refs to v26.1.2 by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/795
* [Cli] Add Header banner message class and update all commands to use it by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/796
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/797

## [v26.1.0](https://github.com/valkyrjaio/valkyrja/compare/v26.0.0...v26.1.0) - 2026-05-05

* [Documentation] Update readme by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/701
* [CI] Ensure only v26+ branches can cherry-pick to from by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/703
* [CI] Remove churn and phpmetrics outdated checks by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/704
* [CI] Add destination branch to cherry-pick workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/705
* [CI] Fix conditional in cherry-pick workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/706
* [CI] Fix conditional in cherry-pick workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/707
* [CI] Fix conditional in cherry-pick workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/708
* [CI] Fix fetch depth in cherry-pick workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/709
* [CI] Fix destination branch checkout in cherry-pick workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/710
* [CI] Add git user setup step in cherry-pick workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/711
* [Container] Ensure container data has services and singletons by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/712
* [Container] Rename ProviderContract to ServiceProviderContract by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/713
* [Data] Rename data cache objects to unique names by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/714
* [Event] Rename provider to ListenerProviderContract by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/715
* [Cli] Rename provider to CliRouteProviderContract by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/716
* [Http] Rename provider to HttpRouteProviderContract by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/717
* [Application] Rename provider to ComponentProviderContract by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/718
* [All] Rename component providers to avoid same name clashing by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/719
* [Application] Update http and cli entry point run methods by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/720
* [Application] Add persistent worker HTTP support by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/721
* [Throwable] Unique component throwable naming by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/723
* [Provider] Update provider naming convention by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/724
* [Application] Remove unused application commands by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/725
* [Application] Remove ComponentClass constants by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/726
* [Application] Add component provider dependencies capability by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/727
* [Event] Rename Dispatcher* to EventDispatcher* by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/728
* [Container] Update bind and bindSingleton to callable value by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/729
* [Application] Ensure the providers are lists are unique by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/730
* [Event] Update event to use listener handler and remove dispatch by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/731
* [Http] Update routing to use route handler and remove dispatch by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/732
* [Event] Update variable names for clarity by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/733
* [Cli] Update routing to use route handler and remove dispatch by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/734
* [Http/Cli/Event] Update routes/listeners list to always be closures by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/735
* [Http] Refactor route collector by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/736
* [Http/Cli/Event] Rename collections and collectors by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/737
* [CI] Update ci with repo projects for better maintainability by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/738
* [Documentation] Update documentation by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/739
* [GitHub] Update ordering of bump options in release workflow by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/740
* [GitHub] Update release and rebase to master workflows by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/741
* [GitHub] Add workflow to rebase from master by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/742
* [GitHub] Add workflow for automatic dependency updates by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/743
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/745
* [CI] Update CI dependencies by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/746
* [Documentation] Add application structure documentation by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/747
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/748
* [Dependabot] Bump symfony/process from 8.0.3 to 8.0.8 in /.github/ci/churn by [@dependabot](https://github.com/dependabot)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/749
* [Documentation] Update versioning documentation by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/750
* [GitHub] Consolidate CI checks and update release and version branch workflows by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/751
* [Documentation] Rewrite README for Valkyrja framework by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/752
* [Composer] Update Churn, Infection, PHPMD, and PHP Metrics dependencies by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/753
* [Composer] Add missing phpmd composer update script by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/754
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/755
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/756
* [GitHub] Update .github workflow refs to v26.2.1 by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/757
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/759
* [GitHub] Update .github workflow refs to v26.2.2 by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/758
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/760
* [GitHub] Add missing trailing newlines by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/761
* [GitHub] Ensure required workflow files by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/763
* [GitHub] Update .github workflow refs to v26.3.0 by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/764
* [GitHub] Update .github workflow refs to v26.4.0 by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/765
* [Http] Move UploadErrorExceptionMessage constant to more exposed namespace by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/766
* [Application] Refactor Config to explicit interfaces by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/767
* [Validation] Rename Rule::getException() to throwException() by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/768
* [Bin] Remove Bin component; superseded by Sindri by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/769
* [Cli/Http] Resolve config singletons by contract in service providers by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/770
* [Http] Register ListCommand in HttpRoutingCliServiceProvider by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/771
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/772
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/773
* [GitHub] Update ci-phpstan-php workflow refs to v26.1.3 by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/774
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/775
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/776
* [Remove] Data file generators for container, cli, event, and http by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/777
* [GitHub] Update ci-phpunit-php workflow refs to v26.3.0 by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/778
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/779
* [Tests] Remove ServiceProviderTestCase in favor of phpunit package's version by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/780
* [Http] Remove route arguments map in favor of ParameterContract values by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/781
* [Cli] Add RouteContract as second parameter to route handler callable by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/782
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/783
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/784
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/785
* [GitHub] Update ci-phpstan-php workflow refs to v26.1.4 by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/786
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/787
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/788
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/789
* [Composer] Update composer dependencies by [@valkyrja-volundr](https://github.com/valkyrja-volundr)[bot] in https://github.com/valkyrjaio/valkyrja-php/pull/790
* [View] Rename response factory by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/791
* [Http] Rename ResponseFactory(Contract) to RoutingResponseFactory(Contract) by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja-php/pull/792

## [v26.0.0](https://github.com/valkyrjaio/valkyrja/compare/26.0.0...v26.0.0) - 2026-03-31

* [Cli] Deprecate Cli env options by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/687
* [Documentation] Add documentation by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/688
* [Http] Deprecate env in favor of config by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/689
* [Application] Deprecate Env by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/690
* [Documentation] Adding docs by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/691
* [CI] Update phpcodesniffer to v8.28.1 from v8.28.0 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/693
* [CI] Update phpstan to v2.1.45 from v2.1.41 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/694
* [CI] Update phpunit to v13.0.6 from v13.0.5 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/695
* [Composer] Update composer packages by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/696
* [CI] Fix typo where phpstan outdate check was running phparkitect by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/697
* [CI] Update phparkitect to v0.8.0 from v0.7.0 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/698
* [CI] Update the release workflow to prep for v26 by [@MelechMizrachi](https://github.com/MelechMizrachi) in https://github.com/valkyrjaio/valkyrja/pull/692
