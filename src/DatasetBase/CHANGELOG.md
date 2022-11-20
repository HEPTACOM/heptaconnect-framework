# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Add `\Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString` based on class `\Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract` with `\Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection` and `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractClassStringReferenceCollection` to have a string references, that could be a class-string for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add base class `\Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract` to have a type safe way to make class string references for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add base class `\Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract` to have a type safe way to make class string references that are of a subtype for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add `\Heptacom\HeptaConnect\Dataset\Base\EntityType` with `\Heptacom\HeptaConnect\Dataset\Base\EntityTypeCollection` as a type safe subtype class reference to `\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add `\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract::class` as factory method to create an instance of `\Heptacom\HeptaConnect\Dataset\Base\EntityType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add `\Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException`, `\Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidSubtypeClassNameException` and `\Heptacom\HeptaConnect\Dataset\Base\Exception\UnexpectedLeadingNamespaceSeparatorInClassNameException` to reference class-string issues for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add exception code `1655559294` to `\Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract::__construct` when the given class string has a leading namespace separator
- Add exception code `1655559295` to `\Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract::__construct` when the given class string does not refer to an existing class or interface
- Add exception code `1655559296` to `\Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract::__construct` when the given class string is not of the expected type
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::withoutItems` from implementation into the interface `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::withoutItems`
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::chunk` from implementation into the interface `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::chunk`
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::asArray` from implementation into the interface `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::asArray`
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::reverse` from implementation into the interface `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::reverse`
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::isEmpty` from implementation into the interface `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::isEmpty`
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::contains` to check whether the given item is in the collection
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::containsByEqualsCheck` for any extending class to build alternative contains implementations based upon comparison
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::asUnique` to build a collection with items that are not identical to the other items in the collection

### Changed

- Change return type of `\Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface::getForeignEntityType` from `class-string` to `\Heptacom\HeptaConnect\Dataset\Base\EntityType` to improve type safety for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Change return type of `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface::filter` from `Generator` to `static` to improve its code usage for fluent syntax and better accessibility of other collection methods
- Replace type hints to real union types in `\Heptacom\HeptaConnect\Dataset\Base\Date::add`, `\Heptacom\HeptaConnect\Dataset\Base\Date::sub`, `\Heptacom\HeptaConnect\Dataset\Base\Date::setTime` and `\Heptacom\HeptaConnect\Dataset\Base\Date::setTimestamp`
- Add implementation reference to `\Stringable` when `__toString` is already implemented in `\Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract`

### Deprecated

- Deprecate `\Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection::getType` in favour of `\Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection::getEntityType` to improve type safety for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Deprecate `$type` parameter in `\Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection::__construct` to be a string. Prefer an instance of `\Heptacom\HeptaConnect\Dataset\Base\EntityType` for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)

### Removed

- Remove support for `php: 7.4` as it will not receive any updates anymore, it is unlikely to be used. By raising the minimum PHP version we also make use of features introduced by PHP 8.0, which mainly have no effect on public API

### Fixed

### Security

## [0.9.2.0] - 2022-10-16

## [0.9.1.1] - 2022-09-28

### Added

- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::withoutItems` to create safely new instances of the same type but without content
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::chunk` to iterate over the items prepared in a buffer of a certain size
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::asArray` to access the items of the collection as fixed size array
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::reverse` to reverse the order of the collection items
- Add method `\Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection::isEmpty` to check whether the collection is empty without counting
- Add aggregation methods `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection::sum`, `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection::max` and `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection::min` to reduce boilerplate code when aggregating a float collection
- Add aggregation methods `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection::sum`, `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection::max` and `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection::min` to reduce boilerplate code when aggregating an integer collection

## [0.9.1.0] - 2022-08-15

### Added

- Add method `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection::join` to implode strings

## [0.9.0.2] - 2022-04-23

## [0.9.0.1] - 2022-04-19

## [0.9.0.0] - 2022-04-02

### Added

- Add `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait::isAttached` to check for a specific instance of an object in the attachment list
- Add `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait::detach` to remove a specific instance from the attachment list
- Add `\Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface` to match the trait `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait` and add it to `\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract`
- Add class `\Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract` as a base class for various file reference implementations
- Add class `\Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceCollection` as a collection for `\Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract`

### Changed

- Implement possible usage of interface FQCNs as parameter in the methods `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait::hasAttached`, `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait::getAttachment`, `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait::detachByType`
- Set `array-key` type on iterating over collections that implement the `\Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface` to `int` as they only accept iterables keyed by `int`
- Add final modifier to `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\BooleanCollection`, `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection`, `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateTimeCollection`, `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection`, `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection`, `\Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection`, `\Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedBooleanCollection`, `\Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedDateCollection`, `\Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedDateTimeCollection`, `\Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedFloatCollection`, `\Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedIntegerCollection`, `\Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection`, `\Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableBooleanCollection`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateCollection`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateTimeCollection`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableFloatCollection`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableIntegerCollection`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableStringCollection`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableBoolean`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDate`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDateTime`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableFloat`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableInteger`, `\Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableString`, `\Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection`, `\Heptacom\HeptaConnect\Dataset\Base\Date`, `\Heptacom\HeptaConnect\Dataset\Base\Dependency`, `\Heptacom\HeptaConnect\Dataset\Base\DependencyCollection` and `\Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection` to ensure correct usage of implementation. Decoration by their interfaces or base classes is still possible

### Deprecated

- Copy and deprecate `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait::unattach` to `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait::detachByType` for correct usage of English language and distinguish from `\Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait::detach`

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
