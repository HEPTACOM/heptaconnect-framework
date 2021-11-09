# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Add `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract::start` for tracking the start of job processing
- Add `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract::finish` for tracking the stop of job processing
- Add `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract::cleanup` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobPayloadRepositoryContract::cleanup` for cleaning up executed jobs and their payloads
- Add `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult\ReceptionRouteListActionInterface` for listing reception routes by the given `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListCriteria` to return a `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult`
- With storage restructure explained in [this ADR](https://heptaconnect.io/reference/adr/2021-09-25-optimized-storage-actions/) we add `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult\RouteOverviewActionInterface` for listing all routes by the given `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteOverviewCriteria` to return a `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteOverviewResult`
- With storage restructure explained in [this ADR](https://heptaconnect.io/reference/adr/2021-09-25-optimized-storage-actions/) we add `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult\RouteFindByTargetsAndTypeActionInterface` for checking the existence of a route by its components by the given `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteFindByTargetsAndTypeCriteria` to return a `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteFindByTargetsAndTypeResult`
- With storage restructure explained in [this ADR](https://heptaconnect.io/reference/adr/2021-09-25-optimized-storage-actions/) we add `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult\RouteGetActionInterface` for reading metadata of routes by the given `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteGetCriteria` to return a `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteGetResult`
- With storage restructure explained in [this ADR](https://heptaconnect.io/reference/adr/2021-09-25-optimized-storage-actions/) we add `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult\RouteCreateActionInterface` for creating routes by the given `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteCreatePayloads` and `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteCreatePayload` to return a `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteCreateResult`
- With storage restructure explained in [this ADR](https://heptaconnect.io/reference/adr/2021-09-25-optimized-storage-actions/) we add `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult\RouteCapabilityOverviewActionInterface` for listing available route capabilities by the given `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteCapabilityOverviewCriteria` to return a `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteCapabilityOverviewResult`
- Add `\Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability` to hold constant values for route capabilities

### Changed

- Change a parameter name in `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingNodeRepositoryContract::listByTypeAndPortalNodeAndExternalId`, `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingNodeRepositoryContract::listByTypeAndPortalNodeAndExternalIds`, `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingNodeRepositoryContract::create`, `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingNodeRepositoryContract::createList` in global refactoring effort
- Change a parameter name in `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingRepositoryContract::listUnsavedExternalIds` in global refactoring effort
- Change a parameter name of `\Heptacom\HeptaConnect\Storage\Base\Exception\UnsharableOwnerException::__construct` in global refactoring effort and rename the field it is saved to. Additionally change the respective getter method to `\Heptacom\HeptaConnect\Storage\Base\Exception\UnsharableOwnerException::getExpectedEntityType`
- Change a parameter name of `\Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct::__construct` in global refactoring effort and rename the field it is saved to. Additionally change the respective getter method to `\Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct::getEntityType` and `\Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct::getForeignEntityType`. Change the method `\Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct::addOwner` to use the refactored method names
- Change a method name in `\Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface` to `\Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface::getEntityType`
- Change a method call in `\Heptacom\HeptaConnect\Storage\Base\MappingCollection::groupByType` to use the refactored method name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface::getEntityType`
- Change a method call in `\Heptacom\HeptaConnect\Storage\Base\TypedMappingCollection::isValidItem` to use the refactored method name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface::getEntityType`

### Removed

- With storage restructure explained in [this ADR](https://heptaconnect.io/reference/adr/2021-09-25-optimized-storage-actions/) we remove implementation `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\RouteRepositoryContract::listBySourceAndEntityType` in favour of `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListActionInterface::list`, `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteOverviewActionInterface::overview` and `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteFindByTargetsAndTypeActionInterface::find` that allows for optimizations for different use-cases
- With storage restructure explained in [this ADR](https://heptaconnect.io/reference/adr/2021-09-25-optimized-storage-actions/) we remove implementation `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\RouteRepositoryContract::read` in favour of `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult\RouteGetActionInterface::get` that allows for optimizations in the storage implementation
- With storage restructure explained in [this ADR](https://heptaconnect.io/reference/adr/2021-09-25-optimized-storage-actions/) we remove implementation `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\RouteRepositoryContract::create` in favour of `\Heptacom\HeptaConnect\Storage\Base\Contract\ReceptionRouteListResult\RouteCreateActionInterface::create` that allows for optimizations in the storage implementation

## [0.7.0] - 2021-09-25

### Added

- Add methods in `\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract` (`\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract::clear`, `\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract::getMultiple` and  `\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract::deleteMultiple`) to allow PSR simple cache compatibility
- Add contract `\Heptacom\HeptaConnect\Storage\Base\MappingPersister\Contract\MappingPersisterContract`. It must be used with `\Heptacom\HeptaConnect\Storage\Base\MappingPersistPayload`. It can throw `\Heptacom\HeptaConnect\Storage\Base\MappingPersister\Exception\MappingConflictException`.

### Changed

- Change parameter in `\Heptacom\HeptaConnect\Storage\Base\TypedMappingCollection::__construct` to allow iterables to be consumed like its parent class

### Fixed

- Require previously soft-required `bentools/iterable-functions: >=1 <2`
