<?php

namespace Twig\Functions;

use DateTime;
use InvalidArgumentException;

class FormatDate extends TwigFunction
{
    public static function getName(): string
    {
        return "format_date";
    }

    public static function call(?string $date = null, ?string $format = null): mixed
    {
        if ($date === null || $format === null) {
            throw new InvalidArgumentException("format_date() expects 2 arguments!");
        }

        return (new DateTime($date))->format($format);
    }
}
