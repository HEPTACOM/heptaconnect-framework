# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

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
- As `\Closure` has a more defined interface for analyzing compared to `callable` and the expected use-case for short-noted flow components are anonymous functions, the return types changed from `callable` to `\Closure` in `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getBatch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getExtend`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::getRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::getIsAllowed`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::getBatch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::getRun` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken::getRun`
- As `\Closure` has a more defined interface for analyzing compared to `callable` and the expected use-case for short-noted flow components are anonymous functions, the parameter types changed from `callable` to `\Closure` in `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\EmitterBuilder::batch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\EmitterBuilder::run`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\EmitterBuilder::extend`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ExplorerBuilder::run`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ExplorerBuilder::isAllowed`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ReceiverBuilder::batch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ReceiverBuilder::run`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Builder\StatusReporterBuilder::run`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::explorer`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::emitter`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::receiver`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::statusReporter`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::setBatch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::setRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::setExtend`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::setRun`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::setIsAllowed`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::setBatch`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::setRun` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken::setRun`
- Add throwing of exception `\Heptacom\HeptaConnect\Portal\Base\Serialization\Exception\StreamCopyException` to `\Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream::copy`

### Fixed

- Change type hint from `string` to `class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>` for parameters in `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::explorer`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::emitter`, `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent::receiver`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::__construct`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::__construct` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::__construct`
- Change type hint from `string` to `class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>` for return type in `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken::getType`, `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken::getType` and `\Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken::getType`
- Allow missing types in short-noted flow components that are resolved by name by changing `string $parameterType` to `?string $parameterType` in function arguments in `\Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait` and their usages
- Fixe return type hint on `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface::next` to return an iterable of `\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract` instead of `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface` and therefore returns like `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract::receive`

### Removed

- Remove `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\WebhookKeyInterface`
- Remove `\Heptacom\HeptaConnect\Portal\Base\StorageKey\WebhookKeyCollection`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookContextInterface`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookHandlerContract`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookInterface` in favour of new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract`
- Remove `\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookServiceInterface` in favour of new flow component `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract` and `\Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerUrlProviderInterface`

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
