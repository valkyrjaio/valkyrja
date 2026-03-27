# Versioning and Release Process

## Introduction

Valkyrja follows [semantic versioning](https://semver.org/). Releases use a single integer major version number — `25`, `26`, `27` — rather than the traditional `MAJOR.MINOR.PATCH` triple. A new major version is released once per year, and each major version is supported for two years from its release date.

This cadence gives you a predictable upgrade path: you know when a new version is coming, how long your current version will be maintained, and what the support window looks like before you need to plan a migration.

## Release Schedule

| Version   | PHP           | Release             | Bug Fixes Until | Security Fixes Until |
|:----------|:--------------|:--------------------|:----------------|:---------------------|
| 25 (*)    | 8.4 – 8.6     | December 11th, 2025 | Q1 2026         | Q1 2026              |
| 26        | 8.4 – 8.6     | Q1 2026             | Q2 2027         | Q1 2028              |
| 27        | 8.5 – 8.6     | Q1 2027             | Q2 2028         | Q1 2029              |
| 28        | 8.6+          | Q1 2028             | Q2 2029         | Q1 2030              |

(*) Pre-release version. Version 25 is not supported once version 26 is released.

## Support Policy

**Bug fixes** are provided until three months after the next major version is released. This window exists to give applications time to migrate before the previous version goes fully unsupported.

**Security fixes** are provided for two years from the initial release date of each major version. After this period, the version receives no further patches of any kind.

Applications running an end-of-life version should upgrade. Running unsupported software in production means known security vulnerabilities will not be patched.

## Development Branches

Each major version in active development has a corresponding branch in the repository.

| Branch   | Purpose                                                                                              |
|:---------|:-----------------------------------------------------------------------------------------------------|
| `master` | Active development for v26. Open for backwards-incompatible changes and major internal API changes.  |
| `25.x`   | The current stable release series. Open for bug fixes only.                                          |

When a new major version ships, the previous stable branch moves into security-only mode. The `master` branch advances to the next version cycle.

Bug fix contributions should target the stable branch (`25.x`). New features and breaking changes should target `master`.
