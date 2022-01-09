<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

class Date extends \DateTime
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

    /**
     * @param \DateInterval $interval
     *
     * @return Date
     */
    public function add($interval)
    {
        return static::createFromDateTime(parent::add($this->removeTimeFromInterval($interval)));
    }

    /**
     * @param \DateInterval $interval
     *
     * @return Date
     */
    public function sub($interval)
    {
        return static::createFromDateTime(parent::sub($this->removeTimeFromInterval($interval)));
    }

    /**
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $microseconds
     *
     * @phpstan-return static(\DateTime)|false
     *
     * @return static|false
     */
    public function setTime($hour, $minute, $second = 0, $microseconds = 0)
    {
        return parent::setTime(0, 0, 0, 0);
    }

    /**
     * @param int $unixtimestamp
     *
     * @return static
     */
    public function setTimestamp($unixtimestamp)
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
