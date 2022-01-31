# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Add replaceable `\Heptacom\HeptaConnect\TestSuite\Storage\TestCase` to allow changing test case base class
- Add entity fixture classes `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA`, `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityB` and `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityC` to provide a basic dataset
- Add portal fixture classes `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA`, `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB` and `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalC\PortalC` to provide a basic portals
- Add portal extension fixture classes `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionA\PortalExtensionA`, `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionB\PortalExtensionB` and `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\PortalExtension\PortalExtensionC\PortalExtensionC` to provide a basic portal extension
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\JobTestContract` to test basic life cycle of a job
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\PortalExtensionTestContract` to test loading, activation and deactivation of a portal extension
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\PortalNodeTestContract` to test basic life cycle of a portal node
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\RouteTestContract` to test basic life cycle of a route
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\PortalNodeConfigurationTestContract` to test basic life cycle of a portal node configuration