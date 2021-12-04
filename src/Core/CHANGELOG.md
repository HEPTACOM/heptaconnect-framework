# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.8.3] - 2021-12-02

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

### Fixed

- Provide callback-function to \array_filter in `Heptacom\HeptaConnect\Core\Flow\DirectEmissionFlow\DirectEmissionFlow::run` to only filter out primary keys with null and not 0
- `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamDenormalizer` rejects null and empty string as data
- Usage of `\Ramsey\Uuid\Uuid` in `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer` only supported `ramsey/uuid: 3` but composer configuration allowed installation of `ramsey/uuid: 4`. Now it is used cross-compatible to work with `ramsey/uuid: 3 || 4`
- `\Heptacom\HeptaConnect\Core\Configuration\ConfigurationService::setPortalNodeConfiguration` removes nested `null` values and does not store `null` anymore
- Fix automatic prototyping when a portal provides an interface in `\Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\RemoveAutoPrototypedDefinitionsCompilerPass::isPrototypable`

### Removed

- Remove `\Heptacom\HeptaConnect\Core\Webhook\Contract\UrlProviderInterface`
- Remove `\Heptacom\HeptaConnect\Core\Webhook\WebhookContext` in favour of `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContext`
- Remove `\Heptacom\HeptaConnect\Core\Webhook\WebhookContextFactory` in favour of `\Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContextFactory`
- Remove `\Heptacom\HeptaConnect\Core\Webhook\WebhookService`
- Remove interface `\Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface::ensurePersistence` and implementation `\Heptacom\HeptaConnect\Core\Mapping\MappingService::ensurePersistence` in favour of `\Heptacom\HeptaConnect\Storage\Base\MappingPersister\Contract\MappingPersisterContract` 

### Deprecated

- Move `\Heptacom\HeptaConnect\Core\Storage\Normalizer\StreamNormalizer::STORAGE_LOCATION` into `\Heptacom\HeptaConnect\Core\Storage\Contract\StreamPathContract::STORAGE_LOCATION`

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