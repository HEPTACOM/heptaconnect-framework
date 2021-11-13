# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Add composer dependency on `psr/event-dispatcher:^1.0`
- Add method `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface::getEventDispatcher` for reception event processing
- Add method `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface::getPostProcessingBag` to access post-processing data bag
- Add new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract` and `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection`
- Add interface `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface` for new flow component
- Add interface `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerStackInterface` and implementation `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack` for new flow component
- Add log message code `1636735335` to `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract::handleNext` when execution of the next handler failed
- Add log message code `1636735336` to `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract::handleCurrent` when execution of the current handler failed
- Add `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::httpHandler`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::buildHttpHandlers`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\HttpHandlerBuilder` to allow short notation for new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract`
- Add log message code `1636791700` to `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::buildHttpHandlers`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::buildReceivers` and `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::buildEmitters` when building flow components and having a configuration conflict

### Changed

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

### Removed

- Remove `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\WebhookKeyInterface`
- Remove `\Heptacom\HeptaConnect\Portal\Base\StorageKey\WebhookKeyCollection`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookContextInterface`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookHandlerContract`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookInterface` in favour of new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookServiceInterface` in favour of new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract`

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
