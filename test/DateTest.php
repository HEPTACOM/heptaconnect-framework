<?php declare(strict_types=1);

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

    public function testMissingTimeComponentAfterAdd(): void
    {
        $date = new Date();
        static::assertEquals('00:00:00', $date->format('H:i:s'));
        $date = $date->add(new \DateInterval('PT1H'));
        static::assertEquals('00:00:00', $date->format('H:i:s'));
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
}
