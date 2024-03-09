# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to a variation of [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
The version numbers are structured like `GENERATION.MAJOR.MINOR.PATCH`:

* `GENERATION` version when concepts and APIs are abandoned, but brand and project name stay the same,
* `MAJOR` version when you make incompatible API changes and provide an upgrade path,
* `MINOR` version when you add functionality in a backwards compatible manner, and
* `PATCH` version when you make backwards compatible bug fixes.

## [Unreleased]

### Added

- Add composer dependency `heptacom/heptaconnect-dataset-base: self.version`
- Add composer dependency `heptacom/heptaconnect-portal-base: self.version`
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException` to allow as fallback exception when any read operation fails
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasNotFoundException` to identify issues when a portal node alias can not be resolved to a portal node
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyDataNotSupportedException` to identify issues when a string fails to be get into a storage key
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyNotSupportedException` to identify issues when a storage key fails to get converted into a string
- Add supporting service interface `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support\PortalNodeAliasResolverInterface` to resolve portal node aliases
- Add supporting service interface `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support\StorageKeyAccessorInterface` to convert storage keys and check their existence

### Changed

### Deprecated

### Removed

### Fixed

### Security
