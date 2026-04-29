<?php

namespace App\Lib;

use DateTime;
use Exception;

class Hijri
{
    public const KABISA_YEAR_REMAINDERS = [2, 5, 8, 10, 13, 16, 19, 21, 24, 27, 29];
    public const DAYS_IN_YEAR = [30, 59, 89, 118, 148, 177, 207, 236, 266, 295, 325];
    public const DAYS_IN_30_YEARS = [
        354, 708, 1063, 1417, 1771, 2126, 2480, 2834, 3189, 3543,
        3898, 4252, 4606, 4961, 5315, 5669, 6024, 6378, 6732, 7087,
        7441, 7796, 8150, 8504, 8859, 9213, 9567, 9922, 10276, 10631,
    ];

    public const MONTH_NAMES = [
        "Moharram al-Haraam",
        "Safar al-Muzaffar",
        "Rabi al-Awwal",
        "Rabi al-Akhar",
        "Jumada al-Ula",
        "Jumada al-Ukhra",
        "Rajab al-Asab",
        "Shabaan al-Kareem",
        "Shehrullah al-Moazzam",
        "Shawwal al-Mukarram",
        "Zilqadah al-Haraam",
        "Zilhajjah al-Haraam"
    ];

    public const ARABIC_MONTH_NAMES = [
        "محرم الحرام",
        "صفر المظفر",
        "ربيع الاول",
        "ربيع الاْخر",
        "جمادى الاولى",
        "جمادى الاخرى",
        "رجب الاصب",
        "شعبان الكريم",
        "شهرالله المعظم",
        "شوال المکرم",
        "ذي القعدة الحرام",
        "ذي الحجة الحرام"
    ];

    public int $year;
    public int $month;
    public int $day;

    public function __construct(int $year, int $month, int $day)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    public function format(string $locale = 'en'): string
    {
        if ($locale === 'ar') {
            return "{$this->day} " . self::ARABIC_MONTH_NAMES[$this->month] . " {$this->year} هـ";
        }
        return "{$this->day} " . self::MONTH_NAMES[$this->month] . " {$this->year} H";
    }

    public static function isJulian(DateTime $date): bool
    {
        $year = (int)$date->format('Y');
        if ($year < 1582) return true;
        if ($year === 1582) {
            $month = (int)$date->format('n');
            $day = (int)$date->format('j');
            if ($month < 10) return true;
            if ($month === 10 && $day < 5) return true;
        }
        return false;
    }

    public static function gregorianToAJD(DateTime $date): float
    {
        $year = (int)$date->format('Y');
        $month = (int)$date->format('n');
        $day = (int)$date->format('j') +
               (int)$date->format('G') / 24 +
               (int)$date->format('i') / 1440 +
               (int)$date->format('s') / 86400 +
               (int)$date->format('v') / 86400000;

        if ($month < 3) {
            $year--;
            $month += 12;
        }

        $b = 0;
        if (!self::isJulian($date)) {
            $a = floor($year / 100);
            $b = 2 - $a + floor($a / 4);
        }

        return floor(365.25 * ($year + 4716)) +
               floor(30.6001 * ($month + 1)) +
               $day +
               $b -
               1524.5;
    }

    public static function ajdToGregorian(float $ajd): DateTime
    {
        $z = floor($ajd + 0.5);
        $f = $ajd + 0.5 - $z;
        $a = $z;
        if ($z >= 2299161) {
            $alpha = floor(($z - 1867216.25) / 36524.25);
            $a = $z + 1 + $alpha - floor(0.25 * $alpha);
        }
        $b = $a + 1524;
        $c = floor(($b - 122.1) / 365.25);
        $d = floor(365.25 * $c);
        $e = floor(($b - $d) / 30.6001);

        $dayWithFraction = $b - $d - floor(30.6001 * $e) + $f;
        $month = $e < 14 ? $e - 2 : $e - 14;
        $year = $month < 2 ? $c - 4715 : $c - 4716;

        $day = (int)floor($dayWithFraction);
        $hrs = ($dayWithFraction - $day) * 24;
        $min = ($hrs - floor($hrs)) * 60;
        $sec = ($min - floor($min)) * 60;
        $msc = ($sec - floor($sec)) * 1000;

        $date = new DateTime();
        $date->setDate((int)$year, (int)$month, (int)$day);
        $date->setTime((int)floor($hrs), (int)floor($min), (int)floor($sec), (int)round($msc * 1000));
        return $date;
    }

    public static function isKabisa(int $year): bool
    {
        return in_array($year % 30, self::KABISA_YEAR_REMAINDERS);
    }

    public static function daysInMonth(int $year, int $month): int
    {
        return ($month === 11 && self::isKabisa($year)) || $month % 2 === 0 ? 30 : 29;
    }

    public function dayOfYear(): int
    {
        return $this->month === 0 ? $this->day : self::DAYS_IN_YEAR[$this->month - 1] + $this->day;
    }

    public function toAJD(): float
    {
        $y30 = floor($this->year / 30.0);
        $ajd = 1948083.5 + $y30 * 10631 + $this->dayOfYear();
        if ($this->year % 30 !== 0) {
            $ajd += self::DAYS_IN_30_YEARS[($this->year % 30) - 1];
        }
        return $ajd;
    }

    public static function fromAJD(float $ajd): self
    {
        $left = floor($ajd - 1948083.5);
        $y30 = (int)floor($left / 10631.0);
        $left -= $y30 * 10631;

        $i = 0;
        while ($i < 30 && $left > self::DAYS_IN_30_YEARS[$i]) {
            $i++;
        }
        $year = (int)round($y30 * 30 + $i);
        if ($i > 0) {
            $left -= self::DAYS_IN_30_YEARS[$i - 1];
        }

        $i = 0;
        while ($i < 12 && $left > (self::DAYS_IN_YEAR[$i] ?? 355)) {
            $i++;
        }
        $month = $i;
        $date = $i > 0 ? (int)round($left - self::DAYS_IN_YEAR[$i - 1]) : (int)round($left);

        return new self($year, $month, $date);
    }

    public static function fromGregorian(DateTime $date): self
    {
        return self::fromAJD(self::gregorianToAJD($date));
    }

    public function toGregorian(): DateTime
    {
        return self::ajdToGregorian($this->toAJD());
    }
}
