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
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException` to allow as fallback exception when any write operation fails
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalExtensionIsAlreadyInactiveOnPortalNodeException` to identify portal extension state change to inactive is not possible as it is already deactivated
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasIsAlreadyAssignedException` to identify exceptions when writing portal node aliases are not unique
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasNotFoundException` to identify issues when a portal node alias can not be resolved to a portal node
- Add `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAlreadyExistsException` to identify issues when a route already exists
- Add supporting service interface `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support\PortalNodeAliasResolverInterface` to resolve portal node aliases
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Portal\PortalEntityListUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult` to list supported entity types in a fresh portal node stack of the given criteria
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeExtensionBrowseUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseCriteria` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse\PortalNodeExtensionBrowseResult` to list portal extension on a portal node and their active state
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeStatusReportUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayload` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportResult` to report any status on a portal node
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeExtensionActivateUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionActivatePayload` to activate portal extensions on a portal node
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeExtensionDeactivateUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionActivate\PortalNodeExtensionDeactivatePayloads` to deactivate portal extensions on a portal node
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeAddUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddPayload` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddResult` to add a portal node with an optional alias
- Add UI admin action `\Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route\RouteAddUiActionInterface` with `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection`, `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayload`, `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection` and `\Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResult` to add routes between portal nodes by entity and capabilities

### Changed

### Deprecated

### Removed

### Fixed

### Security
