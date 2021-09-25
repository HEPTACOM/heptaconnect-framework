# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.7.0] - 2021-09-25

### Added

* Add `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::delete` as replacement for `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::unset`. This method returns a boolean instead of throwing exceptions.
* Add composer dependency on `psr/simple-cache:^1.0`

### Changed

* `\Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract::iterate` caches object iteration strategies to improve performance
* `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface` implements `\Psr\SimpleCache\CacheInterface`.
* `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::set` no longer throws exceptions on failure but returns a boolean instead.

### Removed

* `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::unset` has been replaced by `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::delete`.

### Fixed

* `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::reset` now cleans up status reporter building instructions that got previously registered with `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::statusReporter`
* `\Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract::iterate` drops usage of `\spl_object_hash` to not break on garbage collection
