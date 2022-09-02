# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Wrap `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract::supports` in new method `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract::getSupportedEntityType` to provide an instance of `\Heptacom\HeptaConnect\Dataset\Base\EntityType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Wrap `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract::supports` in new method `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract::getSupportedEntityType` to provide an instance of `\Heptacom\HeptaConnect\Dataset\Base\EntityType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Wrap `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract::supports` in new method `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract::getSupportedEntityType` to provide an instance of `\Heptacom\HeptaConnect\Dataset\Base\EntityType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add `\Heptacom\HeptaConnect\Portal\Base\Portal\PortalType` with `\Heptacom\HeptaConnect\Portal\Base\Portal\PortalTypeCollection` as a type safe subtype class reference to `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add `\Heptacom\HeptaConnect\Portal\Base\Portal\SupportedPortalType` as a type safe class reference to `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add exception code `1659729321` to `\Heptacom\HeptaConnect\Portal\Base\Portal\SupportedPortalType` when a class string is neither referencing a `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract` nor `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract` itself
- Add `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract::class` as factory method to create an instance of `\Heptacom\HeptaConnect\Portal\Base\Portal\PortalType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add `\Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType` with `\Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection` as a type safe subtype class reference to `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract::class` as factory method to create an instance of `\Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Wrap `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract::supports` in new method `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract::getSupportedPortal` to provide an instance of `\Heptacom\HeptaConnect\Portal\Base\Portal\SupportedPortalType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)

### Changed

- Change return type of `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface::supports`, `\Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack::supports`, `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface::getEntityType` and `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract::getEntityType` to be `\Heptacom\HeptaConnect\Dataset\Base\EntityType` instead of a string for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Change `$type` parameter in `\Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingComponentCollection::__construct` to be a `\Heptacom\HeptaConnect\Dataset\Base\EntityType` instead of a string for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Change `$entityType` parameter in `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::__construct`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::__construct`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::__construct`, `\Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack::__construct`, `\Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection::bySupport`, `\Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection::bySupport`, `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct::__construct`, `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection::filterByEntityType` and `\Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection::bySupport` to be a `\Heptacom\HeptaConnect\Dataset\Base\EntityType` instead of a string for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Lowered visibility of `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract::supports`, `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract::supports`, `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract::supports` and `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract::supports` to be protected instead of public
- Rename method `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getType` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getEntityType` with new return type for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Rename method `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::getType` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::getEntityType` with new return type for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Rename method `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::getType` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::getEntityType` with new return type for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Change `$portalClass` parameter in `\Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection::bySupport` to be a `\Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract` instead of a string for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)

### Deprecated

- Deprecate method `\Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingComponentCollection::getType` in favour of new method `\Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingComponentCollection::getEntityType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)

### Removed

### Fixed

- Flow components allowed class string, that not necessarily have to reference an entity class, as supported entity type. Therefore `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface::markAsFailed`, `\Heptacom\HeptaConnect\Core\Emission\EmitContext::markAsFailed`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::explorer`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::emitter`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::receiver` and the new `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract::getSupportedEntityType`, `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract::getSupportedEntityType`, `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract::getSupportedEntityType` throw exception `\Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidSubtypeClassNameException` or `\Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException` on invalid input

### Security

## [0.9.1.0] - 2022-08-15

### Added

- Extract similarities of `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract` and `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract` into a new common base class `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract`
- Add `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract::getContainerExcludedClasses` to allow portals and portal extensions to add and remove automatically excluded classes from container auto-prototyping

### Deprecated

- Deprecate `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PathMethodsTrait` as content will be moved to `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract` without replacement trait

### Fixed

- Change order of stack handling and remove fallback value for the reported topic in `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract::report`

## [0.9.0.2] - 2022-04-23

## [0.9.0.1] - 2022-04-19

## [0.9.0.0] - 2022-04-02

### Added

- Add structure to store code origin data in `\Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin`
- Add exception `\Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound` to indicate issues when looking for code origins
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler::getRunMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler::getOptionsMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler::getGetMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler::getPostMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler::getPatchMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler::getPutMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler::getDeleteMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerCodeOriginFinderInterface` to find code origin of `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract`
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter::getRunMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter::getBatchMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter::getExtendMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterCodeOriginFinderInterface` to find code origin of `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract`
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer::getRunMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer::getIsAllowedMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerCodeOriginFinderInterface` to find code origin of `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract`
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Receiver::getRunMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Receiver::getBatchMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverCodeOriginFinderInterface` to find code origin of `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract`
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\StatusReporter::getRunMethod` to expose configured callback for origin access reading
- Add `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterCodeOriginFinderInterface` to find code origin of `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract`
- Add method for portal extensions `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract::isActiveByDefault` to allow for default activity state configuration
- Add supporting filter method `\Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection::bySupport` to filter portal extensions by their supported portal class
- Add new service `Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract` to container as an alternative to `Psr\Http\Client\ClientInterface` with behaviour by configuration with e.g. `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Support\DefaultRequestHeaders`
- Add class `\Heptacom\HeptaConnect\Portal\Base\File\FileReferenceFactoryContract` to create instances of `\Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract`
- Add class `\Heptacom\HeptaConnect\Portal\Base\File\FileReferenceResolverContract` to resolve instances of `\Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract` to instances of `\Heptacom\HeptaConnect\Portal\Base\File\ResolvedFileReferenceContract`
- Add class `\Heptacom\HeptaConnect\Portal\Base\File\ResolvedFileReferenceContract` to access file references in read operations
- Add new service `\Heptacom\HeptaConnect\Portal\Base\File\FileReferenceFactoryContract` to container to create file references from various sources
- Add new service `\Heptacom\HeptaConnect\Portal\Base\File\FileReferenceResolverContract` to container to resolve file references for read operations
- Add methods `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface::withAlias` and `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface::withoutAlias` to flag a portal node key to prefer the display as alias or storage key
- Make `$this` available in closures for short-notation flow-components with `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent`

### Changed

- Use container tags `heptaconnect.flow_component.status_reporter_source`, `heptaconnect.flow_component.emitter_source`, `heptaconnect.flow_component.explorer_source`, `heptaconnect.flow_component.receiver_source`, `heptaconnect.flow_component.web_http_handler_source` instead of `heptaconnect.flow_component.emitter`, `heptaconnect.flow_component.emitter_decorator`, `heptaconnect.flow_component.explorer`, `heptaconnect.flow_component.explorer_decorator`, `heptaconnect.flow_component.receiver`, `heptaconnect.flow_component.receiver_decorator` and `heptaconnect.flow_component.web_http_handler` to collect flow component services
- Short-noted flow components load on first flow component usage instead on container building
- Use instance of `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract` in log context instead of its class in the message in `\Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack::next`
- Use instance of `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract` in log context instead of its class in the message in `\Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerStack::next`
- Use instance of `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract` in log context instead of its class in the message in `\Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack::next`
- Use instance of `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract` in log context instead of its class in the message in `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack::next`
- Add dependency to `\Psr\Log\LoggerInterface` into `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterStack` to log instance of `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract::next`
- Set `array-key` type to return of `\Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection::bySupport`, `\Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection::bySupport`, `\Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection::bySupport`, `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterCollection::bySupportedTopic` and `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection::bySupport` to `int`
- Add final modifier to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Receiver`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\StatusReporter`, `\Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack`, `\Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerStack`, `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct`, `\Heptacom\HeptaConnect\Portal\Base\Profiling\NullProfiler`, `\Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack`, `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterStack` and `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack` to ensure correct usage of implementation. Decoration by their interfaces or base classes is still possible

### Removed

- Remove container service ids `Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection`, `Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection.decorator`, `Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection`, `Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection.decorator`, `Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterCollection`, `Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection`, `Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection.decorator`, `Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection` and `Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection.decorator` due to refactoring of flow component stack building
- Remove contracts and exceptions `\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobServiceInterface`, `\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobRunInterface`, `\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobInterface`, `\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobHandlerContract`, `\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobContextInterface`, `\Heptacom\HeptaConnect\Portal\Base\Cronjob\Exception\InvalidCronExpressionException`, `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface` and `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobRunKeyInterface` as the feature of cronjobs in its current implementation is removed
- Remove deprecated methods `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::canSet` and `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface::canGet`
- Remove unused `\Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappedDatasetEntityCollection`
- Remove deprecated method `Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface::publish`
- Remove unused `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingKeyInterface` and `\Heptacom\HeptaConnect\Portal\Base\StorageKey\MappingKeyCollection`
- Move unused `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface` and `\Heptacom\HeptaConnect\Portal\Base\StorageKey\RouteKeyCollection` to package `heptacom/heptaconnect-storage-base` as `\Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface` and `\Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection`

## [0.8.5] - 2021-12-28

## [0.8.4] - 2021-12-16

### Removed

- Remove the code for unit tests, configuration for style checks as well as the Makefile

## [0.8.3] - 2021-12-02

## [0.8.2] - 2021-11-25

## [0.8.1] - 2021-11-22

## [0.8.0] - 2021-11-22

### Added

- Add composer dependency on `ext-mbstring:*`
- Add composer dependency on `psr/event-dispatcher:^1.0`
- Add post-processing data bag class `\Heptacom\HeptaConnect\Portal\Base\Reception\Support\PostProcessorDataBag`
- Add method `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface::getEventDispatcher` for reception event processing
- Add method `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface::getPostProcessingBag` to access post-processing data bag
- Add exception code `1636887426` to `\Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream::copy` when source stream is invalid
- Add exception code `1636887427` to `\Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream::copy` when source stream can't be read from
- Add exception code `1636887428` to `\Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream::copy` when result stream can't be created
- Add exception code `1636887429` to `\Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream::copy` when interim stream can't be created
- Add new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract` and `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection`
- Add interface `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface` for new flow component
- Add interface `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerStackInterface` and implementation `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack` for new flow component
- Add log message code `1636735335` to `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract::handleNext` when execution of the next handler failed
- Add log message code `1636735336` to `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract::handleCurrent` when execution of the current handler failed
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::httpHandler`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::buildHttpHandlers`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\HttpHandlerBuilder` to allow short notation for new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract`
- Add log message code `1636791700` to `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::buildHttpHandlers`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::buildReceivers` and `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::buildEmitters` when building flow components and having a configuration conflict
- Add `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerUrlProviderInterface` to resolve URLs for flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract` paths
- Add exception `\Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException` to group cases when short-noted closures are return incorrect values
- Add exception code `1637017868` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter::batch` when short-noted batch method returns an invalid value in iteration
- Add exception code `1637017869` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter::batch` when short-noted batch method returns invalid value
- Add exception code `1637017870` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter::run` when short-noted run method returns invalid value
- Add exception code `1637017871` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter::extend` when short-noted extend method returns invalid value
- Add exception code `1637034100` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer::run` when short-noted run method returns an invalid value in iteration
- Add exception code `1637034101` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer::run` when short-noted run method returns invalid value
- Add exception code `1637034102` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer::isAllowed` when short-noted isAllowed method returns invalid value
- Add exception code `1637440327` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler` when any short-noted method returns invalid value
- Add exception code `1637036888` to `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\StatusReporter::run` when short-noted run method returns invalid value

### Changed

- Change parameter name of `\Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface::publish` from `$datasetEntityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface::markAsFailed` from `$datasetEntityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection::filterByEntityType` from `$datasetEntityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct::__construct` from `$datasetEntityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection::bySupport` from `$entityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack::__construct` from `$entityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection::bySupport` from `$entityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection::bySupport` from `$entityClassName` to `$entityType`
- Change method name from `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct::getDatasetEntityClassName` to `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct::getEntityType`
- Change method name from `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract::getDatasetEntityClassName` to `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract::getEntityType`
- Change method name from `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface::getDatasetEntityClassName` to `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface::getEntityType`
- Change method name from `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection::getDatasetEntityClassNames` to `\Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection::getEntityTypes`
- Change method name from `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\RouteInterface::getEntityClassName` to `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\RouteInterface::getEntityType`
- As `\Closure` has a more defined interface for analyzing compared to `callable` and the expected use-case for short-noted flow components are anonymous functions, the return types changed from `callable` to `\Closure` in `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getBatch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getExtend`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::getRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::getIsAllowed`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::getBatch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::getRun` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken::getRun`
- As `\Closure` has a more defined interface for analyzing compared to `callable` and the expected use-case for short-noted flow components are anonymous functions, the parameter types changed from `callable` to `\Closure` in `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\EmitterBuilder::batch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\EmitterBuilder::run`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\EmitterBuilder::extend`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ExplorerBuilder::run`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ExplorerBuilder::isAllowed`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ReceiverBuilder::batch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ReceiverBuilder::run`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\StatusReporterBuilder::run`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::explorer`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::emitter`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::receiver`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::statusReporter`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::setBatch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::setRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::setExtend`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::setRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::setIsAllowed`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::setBatch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::setRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken::setRun` and `\Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait::resolveArguments`
- Add throwing of exception `\Heptacom\HeptaConnect\Portal\Base\Serialization\Exception\StreamCopyException` to `\Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream::copy`

### Removed

- Remove `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\WebhookKeyInterface`
- Remove `\Heptacom\HeptaConnect\Portal\Base\StorageKey\WebhookKeyCollection`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookContextInterface`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookHandlerContract`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookInterface` in favour of new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookServiceInterface` in favour of new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract` and `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerUrlProviderInterface`
- Remove unused `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\RouteInterface`

### Fixed

- Change type hint from `string` to `class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>` for parameters in `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::explorer`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::emitter`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::receiver`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::__construct`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::__construct` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::__construct`
- Change type hint from `string` to `class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>` for return type in `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getType`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::getType` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::getType`
- Allow missing types in short-noted flow components that are resolved by name by changing `string $parameterType` to `?string $parameterType` in function arguments in `\Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait` and their usages
- Fixe return type hint on `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface::next` to return an iterable of `\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract` instead of `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface` and therefore returns like `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract::receive`

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
