# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.7.0] - 2021-09-25

### Added

* Added methods in `\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract` (`\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract::clear`, `\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract::getMultiple` and  `\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract::deleteMultiple`) to allow PSR simple cache compatibility
* Added contract `\Heptacom\HeptaConnect\Storage\Base\MappingPersister\Contract\MappingPersisterContract`. It must be used with `\Heptacom\HeptaConnect\Storage\Base\MappingPersistPayload`. It can throw `\Heptacom\HeptaConnect\Storage\Base\MappingPersister\Exception\MappingConflictException`.

### Changed

* Changed parameter in `\Heptacom\HeptaConnect\Storage\Base\TypedMappingCollection::__construct` to allow iterables to be consumed like its parent class

### Fixed

* Required previously soft-required `bentools/iterable-functions: >=1 <2`
