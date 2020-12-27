<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Date;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Date
 */
class DateTest extends TestCase
{
    public function testMissingTimeComponent(): void
    {
        $date = new Date();
        static::assertEquals('00:00:00', $date->format('H:i:s'));
        $date->setTime(1, 15);
        static::assertEquals('00:00:00', $date->format('H:i:s'));
    }

    public function testMissingTimeComponentFromDateTime(): void
    {
        $date = Date::createFromDateTime(new \DateTime());
        static::assertEquals('00:00:00', $date->format('H:i:s'));
    }

    public function testMissingTimeComponentFromDateTimeFormat(): void
    {
        $date = Date::createDateFromFormat(\DateTimeInterface::ATOM, (new \DateTime())->format(\DateTimeInterface::ATOM));
        static::assertNotNull($date);
        static::assertEquals('00:00:00', $date->format('H:i:s'));
    }

    public function testFailOnWrongFormatInput(): void
    {
        $date = Date::createDateFromFormat(\DateTimeInterface::ATOM, '');
        static::assertNull($date);
    }

    public function testMissingTimeComponentAfterAddButKeepDate(): void
    {
        $date = new Date();
        $dateOriginal = new \DateTime();

        static::assertEquals('00:00:00', $date->format('H:i:s'));
        $interval = new \DateInterval('PT1H');
        $date = $date->add($interval);
        static::assertEquals('00:00:00', $date->format('H:i:s'));
        static::assertEquals($interval->format('%h'), '1');
        $date = $date->add(new \DateInterval('P5DT5H'));
        $dateOriginal = $dateOriginal->add(new \DateInterval('P5D'));

        static::assertNotEquals($dateOriginal->format(\DateTimeInterface::ATOM), $date->format(\DateTimeInterface::ATOM));
    }

    public function testMissingTimeComponentAfterSub(): void
    {
        $date = new Date();
        static::assertEquals('00:00:00', $date->format('H:i:s'));
        $date->sub(new \DateInterval('PT1H'));
        static::assertEquals('00:00:00', $date->format('H:i:s'));
    }

    public function testMissingTimeComponentAsDateTime(): void
    {
        $date = new Date();
        static::assertEquals('00:00:00', $date->format('H:i:s'));
        $date->add(new \DateInterval('PT1H'));
        static::assertEquals('00:00:00', $date->asDateTime()->format('H:i:s'));
    }

    public function testMissingTimeComponentFromImmutable(): void
    {
        $date = Date::createDateFromImmutable(new \DateTimeImmutable());
        static::assertEquals('00:00:00', $date->format('H:i:s'));
        $date->add(new \DateInterval('PT1H'));
        static::assertEquals('00:00:00', $date->asDateTime()->format('H:i:s'));
    }

    public function testMissingTimeComponentFromTimestamp(): void
    {
        $date = new Date('@1591222533', new \DateTimeZone('UTC'));
        static::assertEquals('2020-06-03T00:00:00+00:00', $date->format(\DateTimeInterface::ATOM));
    }

    public function testMissingTimeComponentFromTimestampSetter(): void
    {
        $date = new Date('now', new \DateTimeZone('UTC'));
        $date->setTimestamp(1591222533);
        static::assertEquals('2020-06-03T00:00:00+00:00', $date->format(\DateTimeInterface::ATOM));
    }
}
