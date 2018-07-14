<?php

namespace IntheJ\Util\Date;

use \AssertionError as AssertionError;
use \DateTime as DateTime;
use \DateInterval as DateInterval;
use \InvalidArgumentException as InvalidArgumentException;

/**
 * Class IntervalDateTimeFactory
 *
 * It is responsible for generating DateTime array based on input interval and count.
 *
 * This class is throws an exception if there is an unintended argument.
 * If you have questions about exceptions, check out the following website.
 * @link https://slides.com/inthej/exception#/
 *
 * @package IntheJ\Util\Date
 * @author IntheJ
 * @since 2018. 7. 1. PM 7:36
 */
class IntervalDateTimeFactory
{
    private static $base_date_time;

    /**
     * Avoid object creation by reflection.
     */
    private function __construct()
    {
        throw new AssertionError();
    }

    /**
     * Creates a dateTime array computed based on the input hour interval and count.
     *
     * @param DateTime $date_time
     * @param int $count
     * @param int $hours
     * @return array
     */
    public static function createDateTimeArrayByHour(DateTime $date_time, $count, $hours)
    {
        self::checkInvalidIntervalCount($count);
        self::checkInvalidIntervalSpecNumber($hours);
        define('INTERVAL_SPEC', "PT{$hours}H");

        return self::createDateTimeArray($date_time, $count, INTERVAL_SPEC);
    }

    /**
     * Creates a DateTime array that is computed based on the input day interval and count.
     *
     * @param DateTime $date_time
     * @param int $count
     * @param int $days
     * @return array
     */
    public static function createDateTimeArrayByDay(DateTime $date_time, $count, $days)
    {
        self::checkInvalidIntervalCount($count);
        self::checkInvalidIntervalSpecNumber($days);
        define('INTERVAL_SPEC', "P{$days}D");

        return self::createDateTimeArray($date_time, $count, INTERVAL_SPEC);
    }

    /**
     * Generates a datetime array based on the interval spec and count.
     *
     * @param DateTime $date_time
     * @param int $count
     * @param string $interval_spec
     * @return array
     */
    private static function createDateTimeArray(DateTime $date_time, $count, $interval_spec)
    {
        self::setBaseDateTime($date_time);

        $result = array();
        for ($i = 0; $i < $count; $i++) {
            array_push($result, self::getBaseDateTime());
            $next_date_time = self::createNextDateTime(self::getBaseDateTime(), $interval_spec);
            self::setBaseDateTime($next_date_time);
        }

        return $result;
    }

    /**
     * @param DateTime $base_date_time
     */
    private static function setBaseDateTime(DateTime $base_date_time)
    {
        self::$base_date_time = $base_date_time;
    }

    /**
     * @return DateTime
     */
    private static function getBaseDateTime()
    {
        return self::$base_date_time;
    }

    /**
     * Generates the next datetime based on the interval_spec of the input datetime.
     *
     * @param DateTime $date_time
     * @param string $interval_spec
     * @return DateTime
     * @throws
     */
    private static function createNextDateTime(DateTime $date_time, $interval_spec)
    {
        $next_date_time = new DateTime();
        $next_date_time->setTimestamp($date_time->getTimestamp());
        $next_date_time->add(new DateInterval($interval_spec));

        return $next_date_time;
    }

    /**
     * Checks that it is an invalid count and throw an exception to terminate the program.
     *
     * @param int $count
     * @throws InvalidIntervalCountException
     */
    private static function checkInvalidIntervalCount($count)
    {
        try {
            if (self::isInvalidIntervalCount($count))
                throw new InvalidIntervalCountException($count);
        } catch (InvalidIntervalCountException $e) {
            print($e->getMessage());
            die("Checking the interval count value. :)");
        }
    }

    /**
     * Checks that it is an invalid interval count.
     *
     * @param int count
     * @return bool
     */
    private static function isInvalidIntervalCount($count)
    {
        return !is_numeric($count);
    }

    /**
     * Checks that it is an invalid interval spec number and throw an exception to terminate the program.
     *
     * @param int $spec_number
     * @throws InvalidIntervalSpecNumberException
     */
    private static function checkInvalidIntervalSpecNumber($spec_number)
    {
        try {
            if (self::isInvalidIntervalSpecNumber($spec_number))
                throw new InvalidIntervalSpecNumberException($spec_number);
        } catch (InvalidIntervalSpecNumberException $e) {
            print($e->getMessage());
            die("Checking the interval spec number value. :)");
        }
    }

    /**
     * Checks that it is an invalid interval spec number.
     *
     * @param int $interval
     * @return bool
     */
    private static function isInvalidIntervalSpecNumber($interval)
    {
        define('MIN_INTERVAL_SPEC_NUMBER', 1);
        return !is_numeric($interval) || $interval < MIN_INTERVAL_SPEC_NUMBER;
    }

    /**
     * Creates the last datetime calculated based on the input time interval and count.
     *
     * @param DateTime $date_time
     * @param int $count
     * @param int $hours
     * @return mixed
     */
    public static function createLastIntervalDateTimeByHour(DateTime $date_time, $count, $hours)
    {
        return array_pop(self::createDateTimeArrayByHour($date_time, $count, $hours));
    }

    /**
     * Creates the last datetime calculated base on the input day interval and count.
     *
     * @param DateTime $date_time
     * @param int $count
     * @param int $days
     * @return mixed
     */
    public static function createLastIntervalDateTimeByDay(DateTime $date_time, $count, $days)
    {
        return array_pop(self::createDateTimeArrayByDay($date_time, $count, $days));
    }

    /**
     * Creates a datetime array computed based in descending order on the input hour interval and count.
     *
     * @param DateTime $date_time
     * @param int $count
     * @param int $hours
     * @return array
     */
    public static function createDescendDateTimeArrayByHour(DateTime $date_time, $count, $hours)
    {
        return array_reverse(self::createDateTimeArrayByHour($date_time, $count, $hours));
    }

    /**
     * Creates a datetime array computed based in descending order on the input day interval and count.
     *
     * @param DateTime $date_time
     * @param int $count
     * @param int $days
     * @return array
     */
    public static function createDescendDateTimeArrayByDay(DateTime $date_time, $count, $days)
    {
        return array_reverse(self::createDateTimeArrayByDay($date_time, $count, $days));
    }

    /**
     * Creates a delayed datetime array computed based on the input day interval and hour.
     *
     * @param DateTime $date_time
     * @param $count
     * @param $delay_count
     * @param $hours
     * @return array
     */
    public static function createDelayDateTimeArrayByHour(DateTime $date_time, $count, $delay_count, $hours)
    {
        self::checkInvalidIntervalCount($count);
        self::checkInvalidDelayCount($delay_count);
        self::checkInvalidIntervalSpecNumber($hours);
        define('INTERVAL_SPEC', "PT{$hours}H");

        return self::createDelayDateTimeArray($date_time, $count, $delay_count,INTERVAL_SPEC);
    }

    /**
     * Creates a delayed datetime array computed based on the input day interval and count.
     *
     * @param DateTime $date_time
     * @param $count
     * @param $delay_count
     * @param $days
     * @return array
     */
    public static function createDelayDateTimeArrayByDay(DateTime $date_time, $count, $delay_count, $days)
    {
        self::checkInvalidIntervalCount($count);
        self::checkInvalidDelayCount($delay_count);
        self::checkInvalidIntervalSpecNumber($days);
        define('INTERVAL_SPEC', "P{$days}D");

        return self::createDelayDateTimeArray($date_time, $count, $delay_count,INTERVAL_SPEC);
    }

    /**
     * Generates the delayed next datetime based on the interval_spec of the input datetime.
     *
     * @param DateTime $date_time
     * @param $count
     * @param $delay_count
     * @param $interval_spec
     * @return array
     */
    private static function createDelayDateTimeArray(DateTime $date_time, $count, $delay_count, $interval_spec)
    {
        self::setBaseDateTime($date_time);

        $result = array();
        for ($i = 0; $i < $count; $i++) {
            $next_date_time = $i < $delay_count
                ? self::getBaseDateTime()
                : self::createNextDateTime(self::getBaseDateTime(), $interval_spec);

            array_push($result, $next_date_time);

            self::setBaseDateTime($next_date_time);
        }

        return $result;
    }

    /**
     * Checks that it is an invalid delay count and throw an exception to terminate the program.
     *
     * @param $delay_count
     * @throws InvalidDelayCountException
     */
    private static function checkInvalidDelayCount($delay_count)
    {
        try {
            if (self::isInvalidDelayCount($delay_count))
                throw new InvalidDelayCountException($delay_count);
        } catch (InvalidDelayCountException $e) {
            print($e->getMessage());
            die("Checking the delay count value. :)");
        }
    }

    /**
     * Checks that it is an invalid delay count.
     *
     * @param $delay_count
     * @return bool
     */
    private static function isInvalidDelayCount($delay_count)
    {
        return !is_numeric($delay_count);
    }

    /**
     * Creates the delayed last datetime calculated based on the input time interval and count.
     *
     * @param DateTime $date_time
     * @param $count
     * @param $delay_count
     * @param $hours
     * @return mixed
     */
    public static function createDelayLastIntervalDateTimeByHour(DateTime $date_time, $count, $delay_count, $hours)
    {
        return array_pop(self::createDelayDateTimeArrayByHour($date_time, $count, $delay_count, $hours));
    }

    /**
     * Creates the delayed last datetime calculated base on the input day interval and count.
     *
     * @param DateTime $date_time
     * @param $count
     * @param $delay_count
     * @param $days
     * @return mixed
     */
    public static function createDelayLastIntervalDateTimeByDay(DateTime $date_time, $count, $delay_count, $days)
    {
        return array_pop(self::createDelayDateTimeArrayByDay($date_time, $count, $delay_count, $days));
    }
}

/**
 * Exception thrown if a interval count does not match with the expected value.
 */
class InvalidIntervalCountException extends InvalidArgumentException
{
    public function __construct($count)
    {
        parent::__construct("Invalid interval count is passed. Interval count value is {$count}.<br>");
    }
}

/**
 * Exception thrown if a delay count does not match with the expected value.
 */
class InvalidDelayCountException extends InvalidArgumentException
{
    public function __construct($delay_count)
    {
        parent::__construct("Invalid delay count is passed. Delay count value is {$delay_count}.<br>");
    }
}

/**
 * Exception thrown if a interval spec number does not match with the expected value.
 */
class InvalidIntervalSpecNumberException extends InvalidArgumentException
{
    public function __construct($spec_number)
    {
        parent::__construct("Invalid interval spec number is passed. Interval spec value is {$spec_number}.<br>");
    }
}
