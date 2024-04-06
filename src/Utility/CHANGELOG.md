# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Move `\Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachableInterface`, `\Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface`, `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface`, `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection`, `\Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection`, `\Heptacom\HeptaConnect\Utility\Collection\Scalar\BooleanCollection`, `\Heptacom\HeptaConnect\Utility\Collection\Scalar\FloatCollection`, `\Heptacom\HeptaConnect\Utility\Collection\Scalar\IntegerCollection`, `\Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection`, `\Heptacom\HeptaConnect\Utility\Date\Date`, `\Heptacom\HeptaConnect\Utility\Date\DateCollection`, `\Heptacom\HeptaConnect\Utility\Date\DateTimeCollection`, `\Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection`, `\Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait`, `\Heptacom\HeptaConnect\Utility\Json\JsonSerializeObjectVarsTrait`, `\Heptacom\HeptaConnect\Utility\Php\SetStateTrait`, `\Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem`, `\Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection`, `\Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedBooleanCollection`, `\Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedFloatCollection`, `\Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedIntegerCollection`, `\Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedStringCollection`, `\Heptacom\HeptaConnect\Utility\Date\TaggedDateCollection`, `\Heptacom\HeptaConnect\Utility\Date\TaggedDateTimeCollection` from `heptacom/heptaconnect-dataset-base` to this package
- Add `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::pushIgnoreInvalidItems` as alternative to `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::push` to push items into collections, without exceptions on invalid items
- Add method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::withoutItems` from implementation into the interface `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::withoutItems`
- Add method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::chunk` from implementation into the interface `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::chunk`
- Add method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::asArray` from implementation into the interface `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::asArray`
- Add method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::reverse` from implementation into the interface `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::reverse`
- Add method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::isEmpty` from implementation into the interface `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::isEmpty`
- Add method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::contains` to check whether the given item is in the collection
- Add method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::containsByEqualsCheck` for any extending class to build alternative contains implementations based upon comparison
- Add method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::asUnique` to build a collection with items that are not identical to the other items in the collection
- Add `\InvalidArgumentException` to `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection` using the new method `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::validateItems` when items are added, that are not compliant with the collection's validation
- Add `\InvalidArgumentException` to `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::offsetGet` and `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::offsetSet` when items are accessed with keys, that are not numeric
- Add base class `\Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract` to have a type safe way to make class string references for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add exception code `1655559294` to `\Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract::__construct` when the given class string has a leading namespace separator
- Add exception code `1655559295` to `\Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract::__construct` when the given class string does not refer to an existing class or interface
- Add base class `\Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract` to have a type safe way to make class string references that are of a subtype for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add exception code `1655559296` to `\Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract::__construct` when the given class string is not of the expected type
- Add `\Heptacom\HeptaConnect\Utility\ClassString\UnsafeClassString` based on class `\Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract` with `\Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection` and `\Heptacom\HeptaConnect\Utility\ClassString\AbstractClassStringReferenceCollection` to have a string references, that could be a class-string for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)
- Add `\Heptacom\HeptaConnect\Utility\ClassString\Exception\InvalidClassNameException`, `\Heptacom\HeptaConnect\Utility\ClassString\Exception\InvalidSubtypeClassNameException` and `\Heptacom\HeptaConnect\Utility\ClassString\Exception\UnexpectedLeadingNamespaceSeparatorInClassNameException` to reference class-string issues for better [type safe class strings](https://heptaconnect.io/reference/adr/2022-06-12-type-safe-class-strings/)

### Changed

- Replace type hints to real union types in `\Heptacom\HeptaConnect\Utility\Date\Date::add`, `\Heptacom\HeptaConnect\Utility\Date\Date::sub`, `\Heptacom\HeptaConnect\Utility\Date\Date::setTime` and `\Heptacom\HeptaConnect\Utility\Date\Date::setTimestamp`
- Change default value from `\Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait::$attachments` a new instance to `null`
- Add possible exception `\InvalidArgumentException` to be thrown from `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::push` and `\Heptacom\HeptaConnect\Utility\Collection\AbstractCollection::__construct` when validating items, that are added to the collection items
- Change return type of `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::filter` from `Generator` to `static` to improve its code usage for fluent syntax and better accessibility of other collection methods
- Change return type of `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::filterValid` from `Generator` to `iterable`
- Change signature of `\Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface::column` to make argument `$valueAccessor` nullable. Passing `null` for this argument will make this method yield its original items.

### Deprecated

### Removed

### Fixed

### Security
