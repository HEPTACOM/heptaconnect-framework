# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Add implementation `\Heptacom\HeptaConnect\Core\Portal\PortalNodeContainerFacade` and its contract `\Heptacom\HeptaConnect\Core\Portal\Contract\PortalNodeContainerFacadeContract` to have a typed interface onto `\Psr\Container\ContainerInterface`
- Extract query matching from `\Heptacom\HeptaConnect\Core\Configuration\PortalNodeConfigurationInstructionProcessor` into `\Heptacom\HeptaConnect\Core\Portal\PackageQueryMatcher` described by `\Heptacom\HeptaConnect\Core\Portal\Contract\PackageQueryMatcherInterface`
- Add implementation `\Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeAliasResolver` for `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support\PortalNodeAliasResolverInterface`
- Add implementation `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi` for `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Portal\PortalEntityListUiActionInterface`
- Add implementation `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionBrowseUi` for `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeExtensionBrowseUiActionInterface`
- Add implementation `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi` for `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeStatusReportUiActionInterface`
- Add implementation `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi` for `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeExtensionActivateUiActionInterface`
- Add implementation `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi` for `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeAddUiActionInterface`
- Add exception code `1655051115` to `\Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeAliasResolver::resolve` when resolving the give portal node alias has no result
- Add exception code `1650142326` to `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi::activate` when the given query does not match any supporting portal extension
- Add exception code `1650142327` to `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi::activate` when all matched portal extensions are already active on the portal node
- Add exception code `1650142328` to `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeExtensionActivateUi::activate` when the portal node key does not refer to a known portal node
- Add exception code `1650718860` to `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi::add` when portal node alias validation fails due to a storage exception
- Add exception code `1650718861` to `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi::add` when the portal node alias is already taken
- Add exception code `1650718862` to `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi::add` when portal node creation fails due to a storage exception
- Add exception code `1650718863` to `\Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi::add` when portal node creation did not create a portal node

### Changed

- Argument of type `\Psr\Container\ContainerInterface` in `\Heptacom\HeptaConnect\Core\Portal\AbstractPortalNodeContext::__construct`, `\Heptacom\HeptaConnect\Core\Exploration\ExploreContext::__construct`, `\Heptacom\HeptaConnect\Core\Emission\EmitContext::__construct`, `\Heptacom\HeptaConnect\Core\Reception\ReceiveContext::__construct`, `\Heptacom\HeptaConnect\Core\StatusReporting\StatusReportingContext::__construct` and `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContext::__construct` is expected of type `\Heptacom\HeptaConnect\Core\Portal\Contract\PortalNodeContainerFacadeContract` instead
- Return type of `\Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory::create` is now `\Heptacom\HeptaConnect\Core\Portal\Contract\PortalNodeContainerFacadeContract` which inherits the previous return type

### Deprecated

### Removed

### Fixed

### Security

## [0.9.0.2] - 2022-04-23

### Fixed

- Portal instances and portal extension instances are not shared across multiple portal node service containers anymore. If these instances are used stateful, portal node A can affect portal node B. All packages we provide have been checked negative against stateful usage of portal and portal extension instances.
- Portal extension stacks are now built for each portal node instead for each portal. This resulted in portal node service containers with active portal extension that have not been set active for the stack's portal node. It only occurs when more than one portal node service containers of the same portal is created in a single PHP process e.g. a message consumption process.
- Portal node configuration for preview portal nodes are now loaded statically again
- Portal node service container for preview portal nodes are now loaded statically again

## [0.9.0.1] - 2022-04-19

### Fixed

- Fix order of portals and portal extensions in `\Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry`
- Fix creating identity error messages in `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\MarkAsFailedPostProcessor::handle`
- Fix a critical error when writing portal node configuration

## [0.9.0.0] - 2022-04-02

### Added

- Implement `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerCodeOriginFinderInterface` in `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerCodeOriginFinder`
- Add exception code `1637607699` in `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerCodeOriginFinder::findOrigin` when http handler is a short-notation http handler and has no configured callback
- Add exception code `1637607700` in `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerCodeOriginFinder::findOrigin` when http handler class cannot be read via reflection
- Add exception code `1637607701` in `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerCodeOriginFinder::findOrigin` when http handler class does not belong to a physical file
- Implement `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterCodeOriginFinderInterface` in `\Heptacom\HeptaConnect\Core\Emission\EmitterCodeOriginFinder`
- Add exception code `1637607653` in `\Heptacom\HeptaConnect\Core\Emission\EmitterCodeOriginFinder::findOrigin` when emitter is a short-notation emitter and has no configured callback
- Add exception code `1637607654` in `\Heptacom\HeptaConnect\Core\Emission\EmitterCodeOriginFinder::findOrigin` when emitter class cannot be read via reflection
- Add exception code `1637607655` in `\Heptacom\HeptaConnect\Core\Emission\EmitterCodeOriginFinder::findOrigin` when emitter class does not belong to a physical file
- Implement `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerCodeOriginFinderInterface` in `\Heptacom\HeptaConnect\Core\Exploration\ExplorerCodeOriginFinder`
- Add exception code `1637421327` in `\Heptacom\HeptaConnect\Core\Exploration\ExplorerCodeOriginFinder::findOrigin` when explorer is a short-notation explorer and has no configured callback
- Add exception code `1637421328` in `\Heptacom\HeptaConnect\Core\Exploration\ExplorerCodeOriginFinder::findOrigin` when explorer class cannot be read via reflection
- Add exception code `1637421329` in `\Heptacom\HeptaConnect\Core\Exploration\ExplorerCodeOriginFinder::findOrigin` when explorer class does not belong to a physical file
- Implement `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverCodeOriginFinderInterface` in `\Heptacom\HeptaConnect\Core\Reception\ReceiverCodeOriginFinder`
- Add exception code `1641079368` in `\Heptacom\HeptaConnect\Core\Reception\ReceiverCodeOriginFinder::findOrigin` when receiver is a short-notation receiver and has no configured callback
- Add exception code `1641079369` in `\Heptacom\HeptaConnect\Core\Reception\ReceiverCodeOriginFinder::findOrigin` when receiver class cannot be read via reflection
- Add exception code `1641079370` in `\Heptacom\HeptaConnect\Core\Reception\ReceiverCodeOriginFinder::findOrigin` when receiver class does not belong to a physical file
- Implement `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterCodeOriginFinderInterface` in `\Heptacom\HeptaConnect\Core\StatusReporting\StatusReporterCodeOriginFinder`
- Add exception code `1641079371` in `\Heptacom\HeptaConnect\Core\StatusReporting\StatusReporterCodeOriginFinder::findOrigin` when status reporter is a short-notation status reporter and has no configured callback
- Add exception code `1641079372` in `\Heptacom\HeptaConnect\Core\StatusReporting\StatusReporterCodeOriginFinder::findOrigin` when status reporter class cannot be read via reflection
- Add exception code `1641079373` in `\Heptacom\HeptaConnect\Core\StatusReporting\StatusReporterCodeOriginFinder::findOrigin` when status reporter class does not belong to a physical file
- Add logger decorator `\Heptacom\HeptaConnect\Core\Component\Logger\FlowComponentCodeOriginFinderLogger` that replaces instances of `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract`, `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract`, `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract`, `\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract` and `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract` within the context with their code origin
- Add new service `Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract` to portal node container as an alternative to `Psr\Http\Client\ClientInterface` with behaviour by configuration e.g. that can throw `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Exception\HttpException` on certain status code
- Add class `\Heptacom\HeptaConnect\Core\Component\Logger\ExceptionCodeLogger` intended as a decorator to prepend the exception code to log messages if available
- Add log message code `1647396033` in `\Heptacom\HeptaConnect\Core\Flow\MessageQueueFlow\MessageHandler::handleJob` when jobs from message cannot be loaded
- Add log message code `1647396034` in `\Heptacom\HeptaConnect\Core\Flow\MessageQueueFlow\MessageHandler::handleJob` when jobs from message cannot be processed
- Add contract `\Heptacom\HeptaConnect\Core\Parallelization\Contract\ResourceLockStorageContract` migrated from `\Heptacom\HeptaConnect\Storage\Base\Contract\ResourceLockStorageContract`
- Add implementation `\Heptacom\HeptaConnect\Core\Parallelization\ResourceLockStorage` for `\Heptacom\HeptaConnect\Core\Parallelization\Contract\ResourceLockStorageContract` that depends on `symfony/lock` which is already required
- Add log message code `1646383738` in `\Heptacom\HeptaConnect\Core\Portal\PortalStorage::list` when reading portal node storage entries fails
- Add implementation `\Heptacom\HeptaConnect\Core\Portal\PreviewPortalNodeStorage` for the interface `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface` to support interactions on `\Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey`
- Add interface `\Heptacom\HeptaConnect\Core\Bridge\File\FileContentsUrlProviderInterface` to provide public URLs for normalized streams
- Add interface `\Heptacom\HeptaConnect\Core\Bridge\File\FileRequestUrlProviderInterface` to prode public URLs for serialized requests
- Add class `\Heptacom\HeptaConnect\Core\File\FileReferenceFactory` to create file references from public URLs, request objects or file contents
- Add class `\Heptacom\HeptaConnect\Core\File\FileReferenceResolver` to resolve file references for read operations
- Add class `\Heptacom\HeptaConnect\Core\File\Reference\ContentsFileReference` as implementation of `\Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract` that is created from file contents
- Add class `\Heptacom\HeptaConnect\Core\File\Reference\PublicUrlFileReference` as implementation of `\Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract` that is created from a public URL
- Add class `\Heptacom\HeptaConnect\Core\File\Reference\RequestFileReference` as implementation of `\Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract` that is created from a PSR-7 request object
- Add class `\Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedContentsFileReference` as implementation of `\Heptacom\HeptaConnect\Portal\Base\File\ResolvedFileReferenceContract` for file references that were created from file contents
- Add class `\Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedPublicUrlFileReference` as implementation of `\Heptacom\HeptaConnect\Portal\Base\File\ResolvedFileReferenceContract` for file references that were created from a public URL
- Add class `\Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedRequestFileReference` as implementation of `\Heptacom\HeptaConnect\Portal\Base\File\ResolvedFileReferenceContract` for file references that were created from a PSR-7 request object
- Add class `\Heptacom\HeptaConnect\Core\Storage\Normalizer\Psr7RequestDenormalizer` to deserialize instances of `\Psr\Http\Message\RequestInterface`
- Add class `\Heptacom\HeptaConnect\Core\Storage\Normalizer\Psr7RequestNormalizer` to serialize instances of `\Psr\Http\Message\RequestInterface`
- Add contract `\Heptacom\HeptaConnect\Core\Storage\Contract\RequestStorageContract` with implementation in `\Heptacom\HeptaConnect\Core\Storage\RequestStorage` to persist and load instances of `\Psr\Http\Message\RequestInterface`
- Add exception code `1647788744` in `\Heptacom\HeptaConnect\Core\File\FileReferenceFactory::fromContents` when the NormalizationRegistry is missing a normalizer for streams
- Add exception code `1648315863` in `\Heptacom\HeptaConnect\Core\File\FileReferenceFactory::fromContents` when the normalizer is unable to serialize the given file contents
- Add exception code `1647788896` in `\Heptacom\HeptaConnect\Core\File\FileReferenceResolver::resolve` when the NormalizationRegistry is missing a denormalizer for streams
- Add exception code `1647789133` in `\Heptacom\HeptaConnect\Core\File\FileReferenceResolver::resolve` when the FileReference has an unsupported source
- Add exception code `1647789503` in `\Heptacom\HeptaConnect\Core\File\ResolvedReference\ResolvedContentsFileReference::getContents` when denormalizing a normalized stream fails
- Add exception code `1647789809` in `\Heptacom\HeptaConnect\Core\Storage\Normalizer\Psr7RequestNormalizer::normalize` when trying to normalize anything other than a request object
- Add exception code `1647790094` in `\Heptacom\HeptaConnect\Core\Storage\RequestStorage::load` when denormalizing a serialized request fails
- Add exception code `1647791094` in `\Heptacom\HeptaConnect\Core\Storage\RequestStorage::load` when a serialized request is not found
- Add exception code `1647791390` in `\Heptacom\HeptaConnect\Core\Storage\RequestStorage::persist` when persisting a serialized request fails
- Add `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract` to define a contract for changing portal node configurations
- Add `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\ClosureInstructionToken` that changes portal node configuration by the given closure
- Add `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\PortalNodeConfigurationHelper` to generate closures for processing configuration sources like json files and environment variables
- Add exception code `1647801828` in return callable from `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\PortalNodeConfigurationHelper::ini` when the ini file can not be loaded and parsed
- Add exception code `1647801829` in return callable from `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\PortalNodeConfigurationHelper::json` when the JSON file can not be loaded and parsed
- Add `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Config` to collect `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract` in a short-notation manner
- Add `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionLoaderInterface` to identify services that provide `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract`
- Add `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\InstructionFileLoader` to provide `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionTokenContract` using `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Config`
- Add exception code `1645611612` in `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\InstructionFileLoader::loadInstructions` when referenced file can not be loaded
- Add `\Heptacom\HeptaConnect\Core\Configuration\PortalNodeConfigurationInstructionProcessor` as `\Heptacom\HeptaConnect\Core\Configuration\Contract\PortalNodeConfigurationProcessorInterface` to change portal node configuration by instructions from given `\Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration\Contract\InstructionLoaderInterface` instances
- Add log message code `1647826121` in `\Heptacom\HeptaConnect\Core\Configuration\PortalNodeConfigurationInstructionProcessor` when an error happens during instruction loading

### Changed

- Replace dependencies in `\Heptacom\HeptaConnect\Core\Flow\MessageQueueFlow\MessageHandler` from `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobPayloadRepositoryContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobGetActionInterface` to improve performance by batching job reading
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Job\Handler\EmissionHandler` from `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobStartActionInterface` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFinishActionInterface` to improve performance by batching job state changes
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Job\Handler\ExplorationHandler` from `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobStartActionInterface` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFinishActionInterface` to improve performance by batching job state changes
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler` from `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobStartActionInterface` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobFinishActionInterface` to improve performance by batching job state changes
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Job\JobDispatcher` from `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobPayloadRepositoryContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobCreateActionInterface` to improve performance by batching job insertion
- Switch storage access in `\Heptacom\HeptaConnect\Core\Portal\PortalRegistry` from `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\PortalNodeRepositoryContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface`
- Use portal node container tags `heptaconnect.flow_component.status_reporter_source`, `heptaconnect.flow_component.emitter_source`, `heptaconnect.flow_component.explorer_source`, `heptaconnect.flow_component.receiver_source`, `heptaconnect.flow_component.web_http_handler_source` instead of `heptaconnect.flow_component.emitter`, `heptaconnect.flow_component.emitter_decorator`, `heptaconnect.flow_component.explorer`, `heptaconnect.flow_component.explorer_decorator`, `heptaconnect.flow_component.receiver`, `heptaconnect.flow_component.receiver_decorator` and `heptaconnect.flow_component.web_http_handler` to collect flow component services
- Short-noted flow components by portals load on first flow component usage instead of on container building using `\Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry`
- Add dependency onto `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface` into `\Heptacom\HeptaConnect\Core\Portal\PortalRegistry` for loading portal extension availability
- Use instance of `\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract` in log context instead of its class in the message in `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder` logger usage
- Use instance of `\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract` in log context instead of its class in the message in `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder` logger usage
- Use instance of `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract` in log context instead of its class in the message in `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder` logger usage
- Use instance of `\Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract` in log context instead of its class in the message in `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilder` logger usage
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Configuration\ConfigurationService` from `\Heptacom\HeptaConnect\Storage\Base\Contract\ConfigurationStorageContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationGetActionInterface` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationSetActionInterface` to improve performance on reading and writing portal node configuration
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler` from `\Heptacom\HeptaConnect\Storage\Base\Contract\EntityMapperContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface` as previous service is renamed
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler` from `\Heptacom\HeptaConnect\Storage\Base\Contract\EntityReflectorContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityReflectActionInterface` as previous service is renamed
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Exploration\ExplorationActor` from `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface`
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsPostProcessor` from `\Heptacom\HeptaConnect\Storage\Base\MappingPersister\Contract\MappingPersisterContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityPersistActionInterface` as previous service is renamed
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory` and `\Heptacom\HeptaConnect\Core\Portal\PortalStorage` from `\Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageClearActionInterface`, `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageDeleteActionInterface`, `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageGetActionInterface` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageListActionInterface`, `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage\PortalNodeStorageSetActionInterface`
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Emission\EmitContext` from `\Heptacom\HeptaConnect\Storage\Core\Mapping\Contract\MappingServiceInterface` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingNodeRepositoryContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityError\IdentityErrorCreateActionInterface` as previous services are removed
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Emission\EmitContextFactory` from `\Heptacom\HeptaConnect\Storage\Core\Mapping\Contract\MappingServiceInterface` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingNodeRepositoryContract` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityError\IdentityErrorCreateActionInterface` as previous services are removed
- Replace dependencies in `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\MarkAsFailedPostProcessor` from `\Heptacom\HeptaConnect\Storage\Core\Mapping\Contract\MappingServiceInterface` to `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityError\IdentityErrorCreateActionInterface` as previous service is removed
- Split argument in `\Heptacom\HeptaConnect\Core\Reception\Contract\ReceiveServiceInterface::receive` of type `\Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappedDatasetEntityCollection` into `\Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection` and `\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface` to state target portal clearly
- Extract caching of `\Heptacom\HeptaConnect\Core\Configuration\ConfigurationService` into new class `\Heptacom\HeptaConnect\Core\Configuration\PortalNodeConfigurationCacheProcessor` using the `\Heptacom\HeptaConnect\Core\Configuration\Contract\PortalNodeConfigurationProcessorInterface` interface
- Make classes final: `\Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationLoader`, `\Heptacom\HeptaConnect\Core\Configuration\ConfigurationService`, `\Heptacom\HeptaConnect\Core\Emission\EmissionActor`, `\Heptacom\HeptaConnect\Core\Emission\EmitContext`, `\Heptacom\HeptaConnect\Core\Emission\EmitContextFactory`, `\Heptacom\HeptaConnect\Core\Emission\EmitService`, `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder`, `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilderFactory`, `\Heptacom\HeptaConnect\Core\Exploration\DirectEmitter`, `\Heptacom\HeptaConnect\Core\Exploration\ExplorationActor`, `\Heptacom\HeptaConnect\Core\Exploration\ExploreContext`, `\Heptacom\HeptaConnect\Core\Exploration\ExploreContextFactory`, `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder`, `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilderFactory`, `\Heptacom\HeptaConnect\Core\Exploration\ExploreService`, `\Heptacom\HeptaConnect\Core\Flow\DirectEmissionFlow\DirectEmissionFlow`, `\Heptacom\HeptaConnect\Core\Job\Handler\EmissionHandler`, `\Heptacom\HeptaConnect\Core\Job\Handler\ExplorationHandler`, `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler`, `\Heptacom\HeptaConnect\Core\Job\DelegatingJobActor`, `\Heptacom\HeptaConnect\Core\Job\JobDispatcher`, `\Heptacom\HeptaConnect\Core\Mapping\MappingNodeStruct`, `\Heptacom\HeptaConnect\Core\Mapping\MappingStruct`, `\Heptacom\HeptaConnect\Core\Mapping\Publisher`, `\Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\AddPortalConfigurationBindingsCompilerPass`, `\Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\AllDefinitionDefaultsCompilerPass`, `\Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\BuildDefinitionForFlowComponentRegistryCompilerPass`, `\Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\RemoveAutoPrototypedDefinitionsCompilerPass`, `\Heptacom\HeptaConnect\Core\Portal\PortalConfiguration`, `\Heptacom\HeptaConnect\Core\Portal\PortalFactory`, `\Heptacom\HeptaConnect\Core\Portal\PortalRegistry`, `\Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerBuilder`, `\Heptacom\HeptaConnect\Core\Portal\PortalStorage`, `\Heptacom\HeptaConnect\Core\Portal\PreviewPortalNodeStorage`, `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\MarkAsFailedPostProcessor`, `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsPostProcessor`, `\Heptacom\HeptaConnect\Core\Reception\ReceiveContext`, `\Heptacom\HeptaConnect\Core\Reception\ReceiveContextFactory`, `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder`, `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilderFactory`, `\Heptacom\HeptaConnect\Core\Reception\ReceiveService`, `\Heptacom\HeptaConnect\Core\Reception\ReceptionActor`, `\Heptacom\HeptaConnect\Core\StatusReporting\StatusReportingContext`, `\Heptacom\HeptaConnect\Core\StatusReporting\StatusReportingContextFactory`, `\Heptacom\HeptaConnect\Core\StatusReporting\StatusReportingService`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\ScalarDenormalizer`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\ScalarNormalizer`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableCompressDenormalizer`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableDenormalizer`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableNormalizer`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamDenormalizer`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer`, `\Heptacom\HeptaConnect\Core\Storage\NormalizationRegistry`, `\Heptacom\HeptaConnect\Core\Support\EntityStatus`, `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContext`, `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContextFactory`, `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilder`, `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilderFactory`, `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleService` and `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlingActor`

### Removed

- Remove separation of source flow components and decorator flow components in `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder`, `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder`, `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder` and `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilder`. First flow component in list is always the source
- Remove portal node container service ids `Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection`, `Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection.decorator`, `Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection`, `Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection.decorator`, `Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterCollection`, `Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection`, `Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection.decorator`, `Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection` and `Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection.decorator` due to refactoring of flow component stack building
- Remove dependency on `\Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent` in `\Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerBuilder`
- Remove classes `\Heptacom\HeptaConnect\Core\Cronjob\CronjobContext`, `\Heptacom\HeptaConnect\Core\Cronjob\CronjobContextFactory` and `\Heptacom\HeptaConnect\Core\Cronjob\CronjobService` as the feature of cronjobs in its current implementation is removed
- Remove composer dependency `dragonmantank/cron-expression`
- Remove unused implementation `\Heptacom\HeptaConnect\Core\Mapping\MappingService::get` of `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::get`
- Remove unused implementation `\Heptacom\HeptaConnect\Core\Mapping\MappingService::save` of `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::save`
- Remove unused implementation `\Heptacom\HeptaConnect\Core\Mapping\MappingService::reflect` of `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::reflect`
- Remove unused implementation `\Heptacom\HeptaConnect\Core\Mapping\MappingService::addException` of `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::addException`
- Remove `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::getListByExternalIds` in favour of `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface::map`
- Remove `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::merge`, `\Heptacom\HeptaConnect\Core\Mapping\Exception\MappingNodeAreUnmergableException` and `\Heptacom\HeptaConnect\Core\Mapping\Exception\MappingNodeNotCreatedException` in favour of `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityOverviewActionInterface` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityPersistActionInterface`
- Remove log message code `1631563639`, `1631563699`, `1631565446` and `1631565376` from `\Heptacom\HeptaConnect\Core\Portal\PortalStorage`
- Remove deprecated methods `\Heptacom\HeptaConnect\Core\Portal\PortalStorage::canSet` and `\Heptacom\HeptaConnect\Core\Portal\PortalStorage::canGet`
- Remove unused `\Heptacom\HeptaConnect\Core\Router\CumulativeMappingException`
- Remove dependency on `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingNodeRepositoryContract` in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler`
- Remove deprecated method `Heptacom\HeptaConnect\Core\Mapping\Publisher::publish` inherited from `Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface::publish`

### Fixed

- Portal node extensions can supply source flow components for data types that have not been introduced by the decorated portal
- All aliases in the dependency-injection container for portals are now public. This enables injection of aliased services in short-notation flow-components.

## [0.8.6] - 2022-03-07

### Fixed

- Prevent leak of `\Heptacom\HeptaConnect\Portal\Base\Reception\Support\PostProcessorDataBag` into subsequent iterations of `\Heptacom\HeptaConnect\Core\Reception\ReceptionActor::performReception`. Every entry of `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\MarkAsFailedData` is now only handled once.

## [0.8.5] - 2021-12-28

### Fixed

- Change composer dependency `bentools/iterable-functions: >=1 <2` to `bentools/iterable-functions: >=1.4 <2` to ensure availability of `\iterable_map` in a lowest-dependency-version installation
- Change composer dependency `composer/composer: >=1` to `composer/composer: >=1.9` to ensure correct composer project and library parsing in a lowest-dependency-version installation
- Change composer dependency `php-http/discovery: ^1.0` to `php-http/discovery: ^1.11` to ensure availability of `\Http\Discovery\Psr17FactoryDiscovery` and `\Http\Discovery\Psr17FactoryDiscovery::findUriFactory` in a lowest-dependency-version installation
- Add composer dependency `symfony/event-dispatcher-contracts: >=1.1` to ensure availability of `\Symfony\Contracts\EventDispatcher\Event` in a lowest-dependency-version installation
- Change composer dependency `symfony/polyfill-php80: >=1.15` to `symfony/polyfill-php80: >=1.16` to ensure availability of `\str_starts_with` a php 7.4 and lowest-dependency-version installation
- Amend signature of `\Heptacom\HeptaConnect\Core\Storage\Normalizer\ScalarDenormalizer::denormalize`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\ScalarDenormalizer::supportsDenormalization`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\ScalarNormalizer::normalize`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\ScalarNormalizer::supportsNormalization`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableCompressDenormalizer::denormalize`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableCompressDenormalizer::supportsDenormalization`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableCompressNormalizer::normalize`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableDenormalizer::denormalize`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableDenormalizer::supportsDenormalization`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableNormalizer::normalize`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableNormalizer::supportsNormalization`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamDenormalizer::denormalize`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamDenormalizer::supportsDenormalization`, `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer::normalize` and `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer::supportsNormalization` to allow installations of `symfony/serializer: >=4` and `symfony/serializer: >= 5`

## [0.8.4] - 2021-12-16

### Removed

- Remove the code for unit tests, configuration for style checks as well as the Makefile

### Fixed

- Provide portal node container services as definition instead of synthetic service to allow decoration for service ids `Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepCloneContract`, `Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract`, `Psr\Http\Client\ClientInterface`, `Psr\Http\Message\RequestFactoryInterface`, `Psr\Http\Message\UriFactoryInterface`, `Psr\Http\Message\ResponseFactoryInterface` and `Psr\Http\Message\StreamFactoryInterface`
- Remove expired keys from the result of `\Heptacom\HeptaConnect\Core\Portal\PortalStorage::getMultiple`

## [0.8.3] - 2021-12-02

### Fixed

- Fix auto-wiring array values from portal configuration

## [0.8.2] - 2021-11-25

### Fixed

- Fix type error during reception when entity with numeric primary key is received

## [0.8.1] - 2021-11-22

### Fixed

- Fix stack building to allow for decorators. Previously when a portal extension had provided a decorator for a flow component, the stack would only contain the decorator and would miss the source component. (`\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder::pushSource`, `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder::pushSource`, `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder::pushSource`, `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilder::pushSource`)

## [0.8.0] - 2021-11-22

### Added

- Add calls to `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract::start` and `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract::finish` in `\Heptacom\HeptaConnect\Core\Job\Handler\EmissionHandler::triggerEmission`, `\Heptacom\HeptaConnect\Core\Job\Handler\ExplorationHandler::triggerExplorations` and `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` to track job states
- Add caching layer to `\Heptacom\HeptaConnect\Core\Configuration\ConfigurationService::getPortalNodeConfiguration`
- Add composer dependency `symfony/event-dispatcher: ^4.0 || ^5.0`
- Add log message `\Heptacom\HeptaConnect\Core\Component\LogMessage::MARK_AS_FAILED_ENTITY_IS_UNMAPPED` with log message code `1637456198` for issues during logging error messages during reception
- Add log message `\Heptacom\HeptaConnect\Core\Component\LogMessage::RECEIVE_NO_SAVE_MAPPINGS_NOT_PROCESSED` for issues after saving mappings after a reception
- Introduce `\Heptacom\HeptaConnect\Core\Event\PostReceptionEvent` for reception new event dispatcher in reception
- Add post-processing type `\Heptacom\HeptaConnect\Portal\Base\Reception\PostProcessing\MarkAsFailedData`
- Implement new method `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface::getEventDispatcher` in `\Heptacom\HeptaConnect\Core\Reception\ReceiveContext::getEventDispatcher`
- Implement new method `\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface::getPostProcessingBag` in `\Heptacom\HeptaConnect\Core\Reception\ReceiveContext::getEventDispatcher`
- Add post-processor base class `\Heptacom\HeptaConnect\Core\Reception\Contract\PostProcessorContract`
- Add post-processing for failed receptions using `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\MarkAsFailedData` and handled in `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\MarkAsFailedPostProcessor`
- Add post-processing for saving mappings after receptions using `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsData` and handled in `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsPostProcessor`
- Extract path building from `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer` and `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamDenormalizer` into new service `\Heptacom\HeptaConnect\Core\Storage\Contract\StreamPathContract`
- Add log messages codes `1634868818`, `1634868819` to `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamDenormalizer`
- Add log message `\Heptacom\HeptaConnect\Core\Component\LogMessage::STORAGE_STREAM_NORMALIZER_CONVERTS_HINT_TO_FILENAME` with the message code `1635462690` to track generated filenames from the stream file storage in `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer`
- Add log exception code `1636503503` to `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` when job has no related route
- Add log exception code `1636503504` to `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` when job has no entity
- Add log exception code `1636503505` to `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` when job refers a non-existing route
- Add log exception code `1636503506` to `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` when job refers to a route that is not configured to allow receptions
- Add log exception code `1636503507` to `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` when job has an entity, that is of a different type than the route's entity type
- Add log exception code `1636503508` to `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` when job has an entity, that has a different primary key than the one saved on the job
- Add web http handler context factory interface `\Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandleContextFactoryInterface` and implementation `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContextFactory` as well as `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContext`
- Add web http stack building interfaces `\Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerStackBuilderFactoryInterface`, `\Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerStackBuilderInterface` and implementations `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilderFactory`, `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilder` for acting with web http handlers
- Add web http service interface `\Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandleServiceInterface` and implementation `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleService` to validate and handle requests
- Add web http actor interface `\Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlingActorInterface` and implementation `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandlingActor` to process any request through a web http handler stack
- Add interface `\Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerUrlProviderFactoryInterface` for bridges to provide implementation as bridges implement routing
- Add log message `\Heptacom\HeptaConnect\Core\Component\LogMessage::WEB_HTTP_HANDLE_NO_THROW` used with log message code `1636845126` when handling the web request triggered an exception in the flow component
- Add log message `\Heptacom\HeptaConnect\Core\Component\LogMessage::WEB_HTTP_HANDLE_NO_HANDLER_FOR_PATH` used with log message code `1636845086` when handling the web request could not match any flow component
- Add log message `\Heptacom\HeptaConnect\Core\Component\LogMessage::WEB_HTTP_HANDLE_DISABLED` used with log message code `1636845085` when route is disabled and still called
- Add `\Heptacom\HeptaConnect\Core\Storage\Exception\GzipCompressException` for cases when gzip related methods fail
- Add exception code `1637432095` in `\Heptacom\HeptaConnect\Core\Storage\Normalizer\SerializableCompressNormalizer::normalize` when `gzcompress` fails to compress
- Add exception code `1637101289` in `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamDenormalizer::denormalize` when file to denormalize does not exist
- Add exception code `1637432853` in `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer::normalize` when object is no `\Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\SerializableStream`
- Add exception code `1637432854` in `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer::normalize` when object does not hold a valid stream
- Add exception code `1637433403` in `\Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\AddPortalConfigurationBindingsCompilerPass::process` when an array_combine call fails that logically should not be able to fail
- Add log message `\Heptacom\HeptaConnect\Core\Component\LogMessage::EMIT_NO_PRIMARY_KEY` used with log message code `1637434358` when emitted entity has no primary key
- Add parameter `$jobKey` in `\Heptacom\HeptaConnect\Core\Job\JobData::__construct`
- Add method `\Heptacom\HeptaConnect\Core\Job\JobData::getJobKey`
- Add service `Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerUrlProviderInterface` to portal container
- Add service `Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection` to portal container
- Add service `Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection.decorator` to portal container
- Add log message code `1637527920` in `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsPostProcessor::handle` when an entity has been received with a primary key but has no mapping data
- Add log message code `1637527921` in `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsPostProcessor::handle` when an entity has been received with a primary key but has invalid mapping data

### Changed

- Change parameter name of `\Heptacom\HeptaConnect\Core\Emission\EmitContext::markAsFailed` from `$datasetEntityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackBuilderFactoryInterface::createEmitterStackBuilder` from `$entityClassName` to `$entityType`, respective change in its implementing class `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilderFactory::createEmitterStackBuilder`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder::__construct` from `$entityClassName` to `$entityType`. Change the field name in corresponding functions that use the field (`\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder::push`, `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder::pushSource`, `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder::pushDecorators`)
- Change parameter name of `\Heptacom\HeptaConnect\Core\Emission\EmitService::getEmitterStack` from `$entityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Exploration\Contract\ExplorerStackBuilderFactoryInterface::createExplorerStackBuilder` from `$entityClassName` to `$entityType`, respective change in its implementing class `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilderFactory::createExplorerStackBuilder`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Exploration\Contract\ExplorationActorInterface::performExploration` from `$entityClassName` to `$entityType`, respective change in its implementing class `\Heptacom\HeptaConnect\Core\Exploration\ExplorationActor::performExploration`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder::__construct` from `$entityClassName` to `$entityType`. Change the field name in corresponding functions that use the field (`\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder::push`, `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder::pushSource`, `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder::pushDecorators`)
- Change parameter name of `\Heptacom\HeptaConnect\Core\Reception\Contract\ReceiverStackBuilderFactoryInterface::createReceiverStackBuilder` from `$entityClassName` to `$entityType`, respective change in its implementing class `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilderFactory::createReceiverStackBuilder`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder::__construct` from `$entityClassName` to `$entityType`. Change the field name in corresponding functions that use the field (`\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder::push`, `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder::pushSource`, `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder::pushDecorators`)
- Change parameter name of `\Heptacom\HeptaConnect\Core\Reception\ReceiveService::getReceiverStack` from `$entityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::get` from `$datasetEntityClassName` to `$entityType`, respective change in its implementing class for `\Heptacom\HeptaConnect\Core\Mapping\MappingService::get`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::getListByExternalIds` from `$datasetEntityClassName` to `$entityType`, respective change in its implementing class for `\Heptacom\HeptaConnect\Core\Mapping\MappingService::getListByExternalIds`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Mapping\MappingNodeStruct::__construct` from `$datasetEntityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Mapping\Publisher::publish` from `$datasetEntityClassName` to `$entityType`
- Change parameter name of `\Heptacom\HeptaConnect\Core\Reception\Support\PrimaryKeyChangesAttachable::__construct` from `$datasetEntityClassName` to `$entityType`
- Change method name from `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract::getDatasetEntityClassName` to `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract::getEntityType`
- Change method name from `\Heptacom\HeptaConnect\Core\Mapping\MappingStruct::getDatasetEntityClassName` to `\Heptacom\HeptaConnect\Core\Mapping\MappingStruct::getEntityType`
- Change method name from `\Heptacom\HeptaConnect\Core\Mapping\MappingNodeStruct::getDatasetEntityClassName` to `\Heptacom\HeptaConnect\Core\Mapping\MappingNodeStruct::getEntityType`
- Change method name from `\Heptacom\HeptaConnect\Core\Mapping\MappingNodeStruct::setDatasetEntityClassName` to `\Heptacom\HeptaConnect\Core\Mapping\MappingNodeStruct::setEntityType`
- Change method name from `\Heptacom\HeptaConnect\Core\Reception\Support\PrimaryKeyChangesAttachable::getForeignDatasetEntityClassName` to `\Heptacom\HeptaConnect\Core\Reception\Support\PrimaryKeyChangesAttachable::getForeignEntityType`
- Change method name from `\Heptacom\HeptaConnect\Core\Reception\Support\PrimaryKeyChangesAttachable::setForeignDatasetEntityClassName` to `\Heptacom\HeptaConnect\Core\Reception\Support\PrimaryKeyChangesAttachable::setForeignEntityType`
- Add dependency onto `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\JobRepositoryContract` into `\Heptacom\HeptaConnect\Core\Job\Handler\EmissionHandler`, `\Heptacom\HeptaConnect\Core\Job\Handler\ExplorationHandler` and `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler` for job tracking
- Add dependency onto `\Psr\Cache\CacheItemPoolInterface` into `\Heptacom\HeptaConnect\Core\Configuration\ConfigurationService` for configuration caching
- Remove parameter `$mappingService` from `\Heptacom\HeptaConnect\Core\Reception\ReceiveContext::__construct` and `\Heptacom\HeptaConnect\Core\Reception\ReceiveContextFactory::__construct` as it is no longer needed
- Add parameter `$postProcessors` to `\Heptacom\HeptaConnect\Core\Reception\ReceiveContext::__construct` and `\Heptacom\HeptaConnect\Core\Reception\ReceiveContextFactory::__construct` to contain every post-processing handler for this context
- Change `\Heptacom\HeptaConnect\Core\Reception\ReceiveContext::markAsFailed` to add `\Heptacom\HeptaConnect\Portal\Base\Reception\PostProcessing\MarkAsFailedData` to the post-processing data bag instead of directly passing to `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::addException`
- Remove parameter `$mappingPersister` from `\Heptacom\HeptaConnect\Core\Reception\ReceptionActor::__construct` as its usage has been moved into `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsPostProcessor`
- Move of saving mappings from `\Heptacom\HeptaConnect\Core\Reception\ReceptionActor::performReception` into `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsPostProcessor::handle`
- Add dependency onto `\Psr\Log\LoggerInterface` into `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer` for logging filename conversions
- Change dependency in `\Heptacom\HeptaConnect\Core\Emission\EmissionActor` from `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\RouteRepositoryContract` into `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Listing\ReceptionRouteListActionInterface` for more performant route lookup
- Change dependency in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler` from `\Heptacom\HeptaConnect\Storage\Base\Contract\Repository\RouteRepositoryContract` into `\Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Get\RouteGetActionInterface` for more performant route reading
- Allow `\Heptacom\HeptaConnect\Core\Job\Contract\ReceptionHandlerInterface::triggerReception` to throw `\Heptacom\HeptaConnect\Core\Job\Exception\ReceptionJobHandlingException`
- Add dependency onto `\Psr\Log\LoggerInterface` into `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler` for logging exceptions
- Add dependency onto `\Psr\Log\LoggerInterface` into `\Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsPostProcessor` for logging unclearmapping scenarios

### Deprecated

- Move `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer::STORAGE_LOCATION` into `\Heptacom\HeptaConnect\Core\Storage\Contract\StreamPathContract::STORAGE_LOCATION`

### Removed

- Remove `\Heptacom\HeptaConnect\Core\Webhook\Contract\UrlProviderInterface`
- Remove `\Heptacom\HeptaConnect\Core\Webhook\WebhookContext` in favour of `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContext`
- Remove `\Heptacom\HeptaConnect\Core\Webhook\WebhookContextFactory` in favour of `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContextFactory`
- Remove `\Heptacom\HeptaConnect\Core\Webhook\WebhookService`
- Remove interface `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::ensurePersistence` and implementation `\Heptacom\HeptaConnect\Core\Mapping\MappingService::ensurePersistence` in favour of `\Heptacom\HeptaConnect\Storage\Base\MappingPersister\Contract\MappingPersisterContract` 

### Fixed

- Provide callback-function to \array_filter in `Heptacom\HeptaConnect\Core\Flow\DirectEmissionFlow\DirectEmissionFlow::run` to only filter out primary keys with null and not 0
- `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamDenormalizer` rejects null and empty string as data
- Usage of `\Ramsey\Uuid\Uuid` in `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer` only supported `ramsey/uuid: 3` but composer configuration allowed installation of `ramsey/uuid: 4`. Now it is used cross-compatible to work with `ramsey/uuid: 3 || 4`
- `\Heptacom\HeptaConnect\Core\Configuration\ConfigurationService::setPortalNodeConfiguration` removes nested `null` values and does not store `null` anymore
- Fix automatic prototyping when a portal provides an interface in `\Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\RemoveAutoPrototypedDefinitionsCompilerPass::isPrototypable`

## [0.7.0] - 2021-09-25

### Added

- Change implementation for `\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface` in `\Heptacom\HeptaConnect\Core\Portal\PortalStorage` to allow PSR simple cache compatibility
- Add log messages codes `1631387202`, `1631387363`, `1631387430`, `1631387448`, `1631387470`, `1631387510`, `1631561839`, `1631562097`, `1631562285`, `1631562928`, `1631563058`, `1631563639`, `1631563699`, `1631565257`, `1631565376`, `1631565446` to `\Heptacom\HeptaConnect\Core\Portal\PortalStorage`
- Add interface `\Heptacom\HeptaConnect\Core\Reception\Contract\ReceiveContextFactoryInterface` to `\Heptacom\HeptaConnect\Core\Reception\ReceiveContextFactory`
- Add interface `\Heptacom\HeptaConnect\Core\Job\Contract\ReceptionHandlerInterface` to `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler`
- Add interface `\Heptacom\HeptaConnect\Core\Job\Contract\ExplorationHandlerInterface` to `\Heptacom\HeptaConnect\Core\Job\Handler\ExplorationHandler`
- Add interface `\Heptacom\HeptaConnect\Core\Job\Contract\EmissionHandlerInterface` to `\Heptacom\HeptaConnect\Core\Job\Handler\EmissionHandler`
- Add interface `\Heptacom\HeptaConnect\Core\Emission\Contract\EmitContextFactoryInterface` to `\Heptacom\HeptaConnect\Core\Emission\EmitContextFactory`
- Add method `\Heptacom\HeptaConnect\Core\Exploration\DirectEmitter::batch` for better performance in direct emissions

### Changed

- `\Heptacom\HeptaConnect\Core\Portal\PortalStorage::get` and `\Heptacom\HeptaConnect\Core\Portal\PortalStorage::set` will now throw exceptions when normalization could not happen
- Add parameter for `\Psr\Log\LoggerInterface` dependency in `\Heptacom\HeptaConnect\Core\Portal\PortalStorage::__construct` and `\Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory::__construct`
- Change type of parameter `\Heptacom\HeptaConnect\Core\Reception\ReceiveContextFactory` to its new interface `\Heptacom\HeptaConnect\Core\Reception\Contract\ReceiveContextFactoryInterface` in `\Heptacom\HeptaConnect\Core\Reception\ReceiveService::__construct`
- Change type of parameter `\Heptacom\HeptaConnect\Core\Job\Handler\EmissionHandler` to its new interface `\Heptacom\HeptaConnect\Core\Job\Contract\EmissionHandlerInterface` in `\Heptacom\HeptaConnect\Core\Job\DelegatingJobActor::__construct`
- Change type of parameter `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler` to its new interface `\Heptacom\HeptaConnect\Core\Job\Contract\ReceptionHandlerInterface` in `\Heptacom\HeptaConnect\Core\Job\DelegatingJobActor::__construct`
- Change type of parameter `\Heptacom\HeptaConnect\Core\Job\Handler\ExplorationHandler` to its new interface `\Heptacom\HeptaConnect\Core\Job\Contract\ExplorationHandlerInterface` in `\Heptacom\HeptaConnect\Core\Job\DelegatingJobActor::__construct`
- Change type of parameter `\Heptacom\HeptaConnect\Core\Emission\EmitContextFactory` to its new interface `\Heptacom\HeptaConnect\Core\Emission\Contract\EmitContextFactoryInterface` in `\Heptacom\HeptaConnect\Core\Emission\EmitService::__construct`
- Change behavior of service `\Heptacom\HeptaConnect\Core\Flow\DirectEmissionFlow\DirectEmissionFlow` to not create mappings anymore
- Remove parameter `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface` from `\Heptacom\HeptaConnect\Core\Flow\DirectEmissionFlow\DirectEmissionFlow::__construct`
- Change method `\Heptacom\HeptaConnect\Core\Reception\ReceptionActor::saveMappings` to use new service `\Heptacom\HeptaConnect\Storage\Base\MappingPersister\Contract\MappingPersisterContract`
- `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder::pushSource` and `\Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder::pushDecorators` don't push explorers onto the stack when they are already in the stack
- `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder::pushSource` and `\Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder::pushDecorators` don't push emitters onto the stack when they already in the stack
- `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder::pushSource` and `\Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder::pushDecorators` don't push receivers onto the stack when they already in the stack

### Removed

- Remove method `\Heptacom\HeptaConnect\Core\Exploration\DirectEmitter::run` as it became obsolete

## [0.6.0] - 2021-07-26

### Added

- Add `\Heptacom\HeptaConnect\Core\Exploration\Contract\ExploreServiceInterface::dispatchExploreJob` to start an exploration as a job via `\Heptacom\HeptaConnect\Core\Job\Contract\JobDispatcherContract::dispatch`
- Add `\Heptacom\HeptaConnect\Core\Job\Handler\ExplorationHandler` to handle exploration jobs `\Heptacom\HeptaConnect\Core\Job\Type\Exploration`
- Add support for handling exploration jobs in `\Heptacom\HeptaConnect\Core\Job\DelegatingJobActor` with using `\Heptacom\HeptaConnect\Core\Job\Handler\ExplorationHandler`
- Add `\Psr\Http\Message\ResponseFactoryInterface` service to the portal containers in `\Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerBuilder` for better http and messaging PSR support for portal developers
- Add `\Psr\Http\Message\StreamFactoryInterface` service to the portal containers in `\Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerBuilder` for better http and messaging PSR support for portal developers

### Changed

- Direct emission and explorations create mappings via `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::getListByExternalIds` on `\Heptacom\HeptaConnect\Core\Exploration\Contract\ExplorationActorInterface::performExploration` when implemented by `\Heptacom\HeptaConnect\Core\Exploration\ExplorationActor::performExploration`

## [0.5.1] - 2021-07-13

### Fixed

- Remove impact of entity primary keys on lock keys in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception`

## [0.5.0] - 2021-07-11

### Added

- Add composer dependency `symfony/yaml: ^4.4|^5.0`
- Add base class `\Heptacom\HeptaConnect\Portal\Base\Flow\DirectEmission\DirectEmissionFlowContract` to `\Heptacom\HeptaConnect\Core\Flow\DirectEmissionFlow` to expose service for portals
- Add classes to hold job data for batch processing `\Heptacom\HeptaConnect\Core\Job\JobData` and `\Heptacom\HeptaConnect\Core\Job\JobDataCollection`
- Add class `\Heptacom\HeptaConnect\Core\Portal\PortalLogger` that can decorate any `\Psr\Log\LoggerInterface` to apply any additional message prefix and context to all logs
- Add `\Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface` to portal node service container
- Add `\Heptacom\HeptaConnect\Portal\Base\Flow\DirectEmission\DirectEmissionFlowContract` to portal node service container

### Changed

- The acting to jobs in `\Heptacom\HeptaConnect\Core\Job\Contract\DelegatingJobActorContract::performJob` will now happen in batches in `\Heptacom\HeptaConnect\Core\Job\Contract\DelegatingJobActorContract::performJobs` and expects different parameters
- The trigger on emission jobs in `\Heptacom\HeptaConnect\Core\Job\Handler\EmissionHandler::triggerEmission` will now happen in batches and expects different parameters
- The trigger on reception jobs in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` will now happen in batches and expects different parameters
- Change signature of `\Heptacom\HeptaConnect\Core\Reception\Contract\ReceptionActorInterface::performReception` to not rely on previously entities bound to `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface` objects
- Change signature of `\Heptacom\HeptaConnect\Core\Reception\ReceiveContext::markAsFailed` to not rely on previously entities bound to `\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface` objects
- Do most of the business logic for reception in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler` to have job related logic less bound to reception processes in general

### Deprecated

- Deprecate cronjobs and therefore mark `\Heptacom\HeptaConnect\Core\Cronjob\CronjobContext`, `\Heptacom\HeptaConnect\Core\Cronjob\CronjobContextFactory`, `\Heptacom\HeptaConnect\Core\Cronjob\CronjobService` as internal
- Deprecate webhooks and therefore mark `\Heptacom\HeptaConnect\Core\Webhook\WebhookContext`, `\Heptacom\HeptaConnect\Core\Webhook\WebhookContextFactory`, `\Heptacom\HeptaConnect\Core\Webhook\WebhookService`, `\Heptacom\HeptaConnect\Core\Webhook\Contact\UrlProviderInterface` as internal

### Removed

- Move `\Heptacom\HeptaConnect\Core\Flow\DirectEmissionFlow\DirectEmissionResult` into the portal base package as `\Heptacom\HeptaConnect\Portal\Base\Flow\DirectEmission\DirectEmissionResult`
- Move `\Heptacom\HeptaConnect\Core\Flow\DirectEmissionFlow\Exception\UnidentifiedEntityException` into the portal base package as `\Heptacom\HeptaConnect\Portal\Base\Flow\DirectEmission\Exception\UnidentifiedEntityException`
- The handling of jobs in `\Heptacom\HeptaConnect\Core\Flow\MessageQueueFlow\MessageHandler::handleJob` does not republish failed jobs anymore. That feature will be added back again in a different form
- The trigger on emission jobs in `\Heptacom\HeptaConnect\Core\Job\Handler\EmissionHandler::triggerEmission` will no longer report back success
- The trigger on reception jobs in `\Heptacom\HeptaConnect\Core\Job\Handler\ReceptionHandler::triggerReception` will no longer report back success
- Remove automatically registered services in `\Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\RemoveAutoPrototypedDefinitionsCompilerPass` that is based on `\Throwable`, `\Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface`, `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface` and `\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract`
