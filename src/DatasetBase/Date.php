<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

final class Date extends \DateTime
{
    public function __construct(string $time = 'now', ?\DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
        $this->setTime(0, 0);
    }

    public static function createFromDateTime(\DateTimeInterface $dateTime): Date
    {
        return new Date('@' . $dateTime->getTimestamp(), $dateTime->getTimezone());
    }

    public static function createDateFromFormat(string $format, string $time, ?\DateTimeZone $timezone = null): ?Date
    {
        $dateTime = parent::createFromFormat($format, $time, $timezone);

        return $dateTime === false ? null : static::createFromDateTime($dateTime);
    }

    public static function createDateFromImmutable(\DateTimeImmutable $datetTimeImmutable): Date
    {
        return static::createFromDateTime(parent::createFromImmutable($datetTimeImmutable));
    }

    public function asDateTime(): \DateTimeInterface
    {
        return new \DateTime('@' . $this->getTimestamp(), $this->getTimezone());
    }

    public function add(\DateInterval $interval): Date
    {
        return static::createFromDateTime(parent::add($this->removeTimeFromInterval($interval)));
    }

    public function sub(\DateInterval $interval): Date
    {
        return static::createFromDateTime(parent::sub($this->removeTimeFromInterval($interval)));
    }

    public function setTime(int $hour, int $minute, int $second = 0, int $microsecond = 0): Date
    {
        return parent::setTime(0, 0, 0, 0);
    }

    public function setTimestamp(int $unixtimestamp): Date
    {
        $time = $unixtimestamp % (24 * 60 * 60);

        return parent::setTimestamp($unixtimestamp - $time);
    }

    private function removeTimeFromInterval(\DateInterval $interval): \DateInterval
    {
        $interval = clone $interval;
        $interval->h = 0;
        $interval->m = 0;
        $interval->s = 0;

        return $interval;
    }
}
