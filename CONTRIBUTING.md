# Contributing to Valkyrja

Anybody who uses Valkyrja can be a contributing member of the community that
develops and releases it; the task of releasing Valkyrja, documentation and
associated websites is a never-ending one. With every release or release
candidate comes a wave of work, which takes a lot of organization and
co-ordination.

You don't need any special access to download, build, debug and begin submitting
PHP code, tests or documentation.

Thank you for your interest in helping us develop, maintain, and release the
Valkyrja framework!

## Submitting Code Changes

If you have a feature, bug fix, documentation update, or any other type of code
change you want to contribute to the framework all we ask is that you ensure
these few things before submitting a PR:

1. By submitting a PR you grant the project the right to include and distribute
   your written code under the MIT license.

2. Please ensure a PR doesn't already exist that covers your change.

3. PRs with no tests will be ignored, or a comment will be left asking you to
   add test.

4. PRs must pass all CI checks. Please run all the CI checks locally.
    1. PHPArkitect: `composer phparkitect`
    2. PHP Code Sniffer: `composer phpcodesniffer`
    3. PHP CS Fixer: `composer phpcsfixer`
    4. PHPStan: `composer phpstan`
    5. PHPUnit: `composer phpunit` or `composer phpunit-coverage` to see that
       you aren't reducing the overall code coverage
    6. Psalm: `composer psalm`
    7. Rector: `composer rector`
    8. If you are changing a composer file please run either
       `composer validate --strict` in root, or
       `composer validate --no-check-publish` for the other composer files

5. Small PRs using atomic, descriptive commits are hugely appreciated as it
   will make reviewing your changes easier for the maintainers.

6. Commit and PR titles should follow the following format:
   `[VALUE] Commit message.`
    1. `[VALUE]` should be the core component you're altering.
        1. Use `[Documentation]` for any documentation changes
        2. Use `[CI]` for any CI related changes
        3. Use `[GitHub]` for any GitHub specific changes
        4. Use `[Git]` for any git related changes
        5. Use `[ModuleName]` for any module changes
           (for example: Container, Http, Cli, etc.)
       6. Use `[Composer]` for composer related changes
       7. Use `[Depcrecation]` for any deprecations
       8. Use `[Functions]` for helper function changes
       9. Use `[VERSION.x]` for version specific changes (`[25.x]` for example)
       10. `[Release]` is reserved for releases
    2. End your commit messages with a period
    3. PR titles should not end in a period

### Branches for Code Changes

| Branch |                                                                                                                     |
|--------|---------------------------------------------------------------------------------------------------------------------|
| master | Active development branch for v26, which is open for backwards incompatible changes and major internal API changes. |
| 25.x   | Is used to release the 25.x series. This is a current stable version and is open for bugfixes only.                 |

## Directory Structure

This will provide an overview of the basic structure of the Valkyrja framework.
Each directory will have a readme file for further information about that
specific directory. The documentation for each module is also within the module
itself.

For modules the main overall structure applies within a module as well.
For example, if a module has files that relate to another module the structure
would follow the module it is replicating. Cli commands for a module would
follow the same structure as the main Cli module `ModuleName\Cli\Command` for
their commands.

The overall directory structure is set by rules within PHPArkitect.

```bash
<valkyrja>/
  └─ .github/               # GitHub specific configurations
    └─ ci                   # CI specific configurations
      ├─ churn              # Churn PHP analysis
      ├─ phparkitect        # PHPArkitect code rules
      ├─ phpcodesniffer     # PHP Code Sniffer standards
      ├─ phpcsfixer         # PHP CS Fixer code style rules
      ├─ phpmetrics         # PHPMetrics analysis
      ├─ phpstan            # PHPStan static analysis
      ├─ phpunit            # PHPUnit tests
      ├─ psalm              # Psalm static analysis
      ├─ rector             # Rector PHP code upgrade tool
      ├─ scrutinizer        # Scrutinizer code analysis config
      └─ suggested          # Configs for suggested composer packages
    ├─ ISSUE_TEMPLATE       # GitHub template files for new issue creation
    ├─ workflows            # GitHub action workflow config files
    └─ ...
  ├─ bin/                   # Composer exported bin files
  ├─ functions/             # Composer exported helper functions
  ├─ pre-commit-hooks/      # Shell scripts to run with pre-commit for git
  └─ src/
    └─ Valkyrja/            # Valkyrja\ namespace directory
      ├─ Api/               # Api module
      ├─ Application/       # Application module
      ├─ Attribute/         # Attribute module
      ├─ Auth/              # Auth module
      ├─ Broadcast/         # Broadcast module
      ├─ Cache/             # Cache module
      └─ Cli/               # Cli module
        ├─ Command/         # Main Cli commands
        ├─ Exception/       # Shared Exception files for the Cli module
        ├─ Interaction/     # Cli interaction module
        ├─ Middleware/      # Cli middlware module
        ├─ Routing/         # Cli routing module
        ├─ Server/          # Cli server module
        └─ ...
      ├─ Container/         # Container module
      ├─ Crypt/             # Crypt module
      ├─ Dispatcher/        # Dispatcher module
      ├─ Event/             # Event module
      ├─ Exception/         # Exception module
      ├─ Filesystem/        # Filesystem module
      └─ Http/              # Http module
        ├─ Client/          # Client (PSR-18 compliant) module
        ├─ Exception/       # Shared Exception files for the Http module
        ├─ Message/         # Http message (PSR-7 compliant-ish) module
        ├─ Middleware/      # Http middleware module
        ├─ Routing/         # Http routing module
        ├─ Server/          # Http server module
        ├─ Struct/          # Http struct module
        └─ ...
      ├─ Jwt/               # Jwt module
      ├─ Log/               # Log module
      ├─ Mail/              # Mail module
      ├─ Notification/      # Notification module
      ├─ Orm/               # Orm module
      ├─ Reflection/        # Reflection module
      ├─ Session/           # Session module
      ├─ Sms/               # Sms module
      ├─ Support/           # Support module
      ├─ Type/              # Type module
      ├─ Validation/        # Validation module
      ├─ View/              # View module
      └─ ...
  └─ tests/                 # Valkyrja\Tests namespace
    ├─ Classes/             # Mock/Stub classes for assisting in tests (follows directory structure of /src/Valkyrja modules)
    ├─ end-to-end/          # phpt test files
    ├─ Functional/          # Functional tests
    ├─ storage/             # Directory used for unit tests
    ├─ Trait/               # Traits for tests
    └─ Unit/                # PHPUnit tests (follows directory structure of /src/Valkyrja modules)
  └─ ...
```

## Getting Help

If you need help with contributing code you can make an [issue][issues url] and
use a title similar to `[Help] Title for what you need help with`.

[issues url]: https://github.com/valkyrjaio/valkyrja/issues
