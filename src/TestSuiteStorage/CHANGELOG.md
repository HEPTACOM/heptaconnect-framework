# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Add replaceable `\Heptacom\HeptaConnect\TestSuite\Storage\TestCase` to allow changing test case base class
- Add entity fixture classes `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA`, `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityB` and `\Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityC` to provide a basic dataset
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\JobTestContract` to test basic life cycle of a job
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\PortalExtensionTestContract` to test loading, activation and deactivation of a portal extension
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\PortalNodeTestContract` to test basic life cycle of a portal node
- Add `\Heptacom\HeptaConnect\TestSuite\Storage\Action\RouteTestContract` to test basic life cycle of a route
