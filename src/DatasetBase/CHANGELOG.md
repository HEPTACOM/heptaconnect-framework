# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Add `\Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface` to match the trait `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait` and add it to `\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract`

## [0.8.5] - 2021-12-28

### Fixed

- Change composer dependency `bentools/iterable-functions: >=1 <2` to `bentools/iterable-functions: >=1.4 <2` to ensure availability of `iterable_map`

## [0.8.4] - 2021-12-16

### Removed

- Remove the code for unit tests, configuration for style checks as well as the Makefile

## [0.8.3] - 2021-12-02

## [0.8.2] - 2021-11-25

## [0.8.1] - 2021-11-22

## [0.8.0] - 2021-11-22

### Changed

- Change composer dependency `bentools/iterable-functions: >=1` to `bentools/iterable-functions: >=1 <2`
- Change method name of `\Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface::getForeignDatasetEntityClassName` to `\Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface::getForeignEntityType`

## [0.7.0] - 2021-09-25

### Changed

- Amend typehint for `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::__construct`, `\Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection::__construct` and `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::push` to improve static code analysis.

### Fixed

- Change signature `\Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection::__construct` to allow iterables instead of array like other collections

## [0.6.0] - 2021-07-26

### Added

- New method `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::column` to improve common cases from `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::map` usage

### Changed

- Amend `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::map` typehint for callables to improve static code analysis 

## [0.5.1] - 2021-07-13

## [0.5.0] - 2021-07-11

### Added

- New composer dependency `opis/closure: ^3.6` to allow serialization of closures
- New class `\Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection` to have a dataset entity collection that ensures to contain a single type only to improve common case `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::filter`
- New class `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\AbstractTranslatableScalarCollection` to allow translations of any collections
- New class `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableBooleanCollection` to allow translations of type `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\BooleanCollection`
- New class `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateCollection` to allow translations of type `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection`
- New class `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateTimeCollection` to allow translations of type `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateTimeCollection`
- New class `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableFloatCollection` to allow translations of type `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection`
- New class `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableIntegerCollection` to allow translations of type `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection`
- New class `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableStringCollection` to allow translations of type `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection`
- New method in `\Heptacom\HeptaConnect\Dataset\Base\Contract\DeferralAwareInterface::copyDeferrals` to copy deferrals from one deferral aware to another one 
- New default implementation of method `\Heptacom\HeptaConnect\Dataset\Base\Contract\DeferralAwareInterface::copyDeferrals` in `\Heptacom\HeptaConnect\Dataset\Base\Support\DeferralAwareTrait`

### Changed

- Rename `\Heptacom\HeptaConnect\Dataset\Base\Translatable\GenericTranslatable` to `\Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable`
- Add `\Heptacom\HeptaConnect\Dataset\Base\Contract\DeferralAwareInterface` to `\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract`

## [0.4.0] - 2021-07-03

## [0.3.1] - 2021-07-02

## [0.3.0] - 2021-07-02
