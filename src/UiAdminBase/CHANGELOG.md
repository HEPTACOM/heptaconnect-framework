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
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract` to identify and pre-structure any browse criteria
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListCriteriaContract` to identify and pre-structure any entity support listing criteria
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListResultContract` to identify and pre-structure any entity support listing result
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNodeExtensionActiveChangePayloadContract` to identify and pre-structure payloads to change the active state for an extension on a portal node
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException` to allow as fallback exception when any write operation fails
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException` to allow as fallback exception when any read operation fails
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionIsAlreadyInactiveOnPortalNodeException` to identify portal extension state change to inactive is not possible as it is already deactivated
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException` to identify issues when portal nodes are referenced but don't exist
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasIsAlreadyAssignedException` to identify exceptions when writing portal node aliases are not unique
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasNotFoundException` to identify issues when a portal node alias can not be resolved to a portal node
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAlreadyExistsException` to identify issues when a route already exists
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAddFailedException` to identify issues when route creation failed logically
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyDataNotSupportedException` to identify issues when a string fails to be get into a storage key
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyNotSupportedException` to identify issues when a storage key fails to get converted into a string
- Add supporting service interface `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support\PortalNodeAliasResolverInterface` to resolve portal node aliases
- Add supporting service interface `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support\StorageKeyAccessorInterface` to convert storage keys and check their existence
- Add UI admin action interface `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface` to ensure UI action related methods, that all UI actions share
- Add UI admin action type `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionTypeCollection` as a type safe subtype class reference to `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add UI admin action payload interface `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface` to allow structs to control, which data needs to be audited
- Add UI admin action context described in `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface` to pass multiple call related properties, that all UI actions share
- Add UI admin action context factory described in `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextFactoryInterface` to create a UI admin action context
- Add UI admin audit context described in `\Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext` to pass multiple auditing related properties, that all UI actions share
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Portal\PortalEntityListUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult` to list supported entity types in a fresh portal node stack of the given criteria
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeExtensionBrowseUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseCriteria` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseResult` to list portal extension on a portal node and their active state
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeStatusReportUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayload` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportResult` to report any status on a portal node
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeExtensionActivateUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload` to activate portal extensions on a portal node
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeExtensionDeactivateUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionDeactivatePayloads` to deactivate portal extensions on a portal node
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeAddUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddPayload` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddResult` to add a portal node with an optional alias
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeEntityListUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListResult` to add a portal node with an optional alias
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route\RouteAddUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection`, `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayload`, `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResult` to add routes between portal nodes by entity and capabilities
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route\RouteBrowseUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseCriteria` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseResult` to query an overview on routes by different criteria
- Add UI admin default provider `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route\RouteAddUiDefaultProviderInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddDefault` to get route add action related default values

### Changed

### Deprecated

### Removed

### Fixed

### Security
