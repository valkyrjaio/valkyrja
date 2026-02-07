<p align="center"><a href="https://valkyrja.io" target="_blank">
    <img src="https://i.imgur.com/bnZA2RT.png" width="400">
</a></p>

# Valkyrja

[Valkyrja][Valkyrja url] is a PHP framework for web and console applications.

About Valkyrja
------------

> This repository contains the core code of the Valkyrja framework.

Valkyrja (pronounced "Valk-ear-ya") is the Old Norse spelling for Valkyrie, a
mythical creature that would guide warriors to Valhalla (the afterlife and a
better place) after death. In a similar sense, the Valkyrja framework guides
your application to be in a better state. Let this fast, light, and robust
framework do the heavy lifting for your app.

<p>
    <a href="https://packagist.org/packages/valkyrja/valkyrja"><img src="https://poser.pugx.org/valkyrja/valkyrja/require/php" alt="PHP Version Require"></a>
    <a href="https://packagist.org/packages/valkyrja/valkyrja"><img src="https://poser.pugx.org/valkyrja/valkyrja/v" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/valkyrja/valkyrja"><img src="https://poser.pugx.org/valkyrja/valkyrja/license" alt="License"></a>
    <!-- <a href="https://packagist.org/packages/valkyrja/valkyrja"><img src="https://poser.pugx.org/valkyrja/valkyrja/downloads" alt="Total Downloads"></a>-->
</p>

### Coverage Status

<p>
    <a href="https://scrutinizer-ci.com/g/valkyrjaio/valkyrja/?branch=master"><img src="https://scrutinizer-ci.com/g/valkyrjaio/valkyrja/badges/quality-score.png?b=master" alt="Scrutinizer"></a>
    <a href="https://coveralls.io/github/valkyrjaio/valkyrja?branch=master"><img src="https://coveralls.io/repos/github/valkyrjaio/valkyrja/badge.svg?branch=master" alt="Coverage Status" /></a>
    <a href="https://shepherd.dev/github/valkyrjaio/valkyrja"><img src="https://shepherd.dev/github/valkyrjaio/valkyrja/coverage.svg" alt="Psalm Shepherd" /></a>
</p>

### Build Status

|                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                            |
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| <a href="https://github.com/valkyrjaio/valkyrja/actions/workflows/phparkitect.yml?query=branch%3Amaster"><img src="https://github.com/valkyrjaio/valkyrja/actions/workflows/phparkitect.yml/badge.svg?branch=master" alt="PHPArkitect Build Status"></a>            | <a href="https://github.com/valkyrjaio/valkyrja/actions/workflows/phpunit.yml?query=branch%3Amaster"><img src="https://github.com/valkyrjaio/valkyrja/actions/workflows/phpunit.yml/badge.svg?branch=master" alt="PHPUnit Build Status"></a>                               |
| <a href="https://github.com/valkyrjaio/valkyrja/actions/workflows/phpcodesniffer.yml?query=branch%3Amaster"><img src="https://github.com/valkyrjaio/valkyrja/actions/workflows/phpcodesniffer.yml/badge.svg?branch=master" alt="PHP Code Sniffer Build Status"></a> | <a href="https://github.com/valkyrjaio/valkyrja/actions/workflows/psalm.yml?query=branch%3Amaster"><img src="https://github.com/valkyrjaio/valkyrja/actions/workflows/psalm.yml/badge.svg?branch=master" alt="Psalm Build Status"></a>                                     |
| <a href="https://github.com/valkyrjaio/valkyrja/actions/workflows/phpcsfixer.yml?query=branch%3Amaster"><img src="https://github.com/valkyrjaio/valkyrja/actions/workflows/phpcsfixer.yml/badge.svg?branch=master" alt="PHP CS Fixer Build Status"></a>             | <a href="https://github.com/valkyrjaio/valkyrja/actions/workflows/rector.yml?query=branch%3Amaster"><img src="https://github.com/valkyrjaio/valkyrja/actions/workflows/rector.yml/badge.svg?branch=master" alt="Rector Build Status"></a>                                  |
| <a href="https://github.com/valkyrjaio/valkyrja/actions/workflows/phpstan.yml?query=branch%3Amaster"><img src="https://github.com/valkyrjaio/valkyrja/actions/workflows/phpstan.yml/badge.svg?branch=master" alt="PHPStan Build Status"></a>                        | <a href="https://github.com/valkyrjaio/valkyrja/actions/workflows/validate-composer.yml?query=branch%3Amaster"><img src="https://github.com/valkyrjaio/valkyrja/actions/workflows/validate-composer.yml/badge.svg?branch=master" alt="Validate Composer Build Status"></a> |

Documentation
-------------

The Valkyrja [documentation][docs url] is baked into the repo so you can
access it even when working offline.

Installation
------------

There are two ways to install the Valkyrja framework.

### Composer

You can either choose to install via composer as a dependency to a new or
existing project.

Run the command below to require the framework in your existing composer json:

```
composer require valkyrja/valkyrja
```

If you are adding Valkyrja to a new blank project there are some files
you'll need to create. You can follow the
[New Project Guide][New Project Guide url] for more details.

### Application Skeleton

Clone the [Valkyrja Application][Valkyrja Application url] and start from there.

Versioning and Release Process
---------------

Valkyrja uses [semantic versioning][semantic versioning url] with a major
release every year, and support for each major version for 2 years from the
date of release.

For more information view our
[Versioning and Release Process documentation][Versioning and Release Process url].

### Supported Versions

Bug fixes will be provided until 3 months after the next major release. Security
fixes will be provided for 2 years after the initial release.

| Version | PHP (*)   | Release             | Bug Fixes Until | Security Fixes Until |
|---------|-----------|---------------------|-----------------|----------------------|
| 25 (**) | 8.4 - 8.6 | December 11th, 2025 | Q1 2026         | Q1 2026              |
| 26      | 8.4 - 8.6 | Q1 2026             | Q2 2027         | Q1 2028              |
| 27      | 8.5 - 8.6 | Q1 2027             | Q2 2028         | Q1 2029              |
| 28      | 8.6+      | Q1 2028             | Q2 2029         | Q1 2030              |

(*) Supported PHP versions
(**) Pre-release that is not supported once v26 is released

Contributing
------------

Valkyrja is an Open Source, community-driven project.

Thank you for your interest in helping us develop, maintain, and release the
Valkyrja framework!

You can find more information in our
[Contributing documentation][contributing url].

Security Issues
---------------

If you discover a security vulnerability within Valkyrja, please follow our
[disclosure procedure][security vulnerabilities url].

License
---------------

The Valkyrja framework is open-sourced software licensed under
the [MIT license][MIT license url]. You can view the
[Valkyrja License here][license url].

[Valkyrja url]: https://valkyrja.io

[github main]: https://github.com/valkyrjaio

[Valkyrja Application url]: https://github.com/valkyrjaio/application

[docs url]: ./src/Valkyrja/README.md

[New Project Guide url]: ./src/Valkyrja/NEW_PROJECT_GUIDE.md

[Versioning and Release Process url]: ./src/Valkyrja/VERSIONING_AND_RELEASE_PROCESS.md

[security vulnerabilities url]: ./SECURITY.md

[semantic versioning url]: https://semver.org/

[MIT license url]: https://opensource.org/licenses/MIT

[license url]: ./LICENSE.md

[contributing url]: ./CONTRIBUTING.md
