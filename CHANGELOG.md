# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

- Change a parameter name of `\Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface::publish` in global refactoring effort
- Change a parameter name of `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface::markAsFailed` in global refactoring effort
- Change a parameter name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection::filterByEntityType` in global refactoring effort
- Change a parameter name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct::__construct` in global refactoring effort and rename the field it is saved to. Additionally change the respective getter method to `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct::getEntityType`
- Change a method name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract` to `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract::getEntityType` in global refactoring effort
- Change a method name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface` to `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface::getEntityType` in global refactoring effort
- Change a method name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection` to `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection::getEntityTypes` in global refactoring effort
- Change a method call in `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection::getEntityTypes` to use the refactored method name `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract::getEntityType`
- Change a method call in `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection::filterByEntityType` to use the refactored method name `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract::getEntityType`
- Change a method call in `\Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappedDatasetEntityCollection::isValidItem` to use the refactored method name `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface::getEntityType`
- Change a method call in `\Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingComponentCollection::isValidItem` to use the refactored method name `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface::getEntityType`

## [0.7.0] - 2021-09-25

### Added

- Add `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::delete` as replacement for `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::unset`. This method returns a boolean instead of throwing exceptions.
- Add composer dependency on `psr/simple-cache:^1.0`

### Changed

- `\Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract::iterate` caches object iteration strategies to improve performance
- `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface` implements `\Psr\SimpleCache\CacheInterface`.
- `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::set` no longer throws exceptions on failure but returns a boolean instead.

### Removed

- `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::unset` has been replaced by `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::delete`.

### Fixed

- `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::reset` now cleans up status reporter building instructions that got previously registered with `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::statusReporter`
- `\Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract::iterate` drops usage of `\spl_object_hash` to not break on garbage collection
