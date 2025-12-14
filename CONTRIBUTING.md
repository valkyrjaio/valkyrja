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
   5. PHPUnit: `composer phpunit` or `composer phpunit-coverage` to see that you
      aren't reducing the overall code coverage
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
   2. End your commit messages with a period
   3. PR titles should not end in a period
