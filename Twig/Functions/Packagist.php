<?php

namespace Twig\Functions;

use Common\PackagistCache;

class Packagist extends TwigFunction
{
    public static function getName(): string
    {
        return "packagist";
    }

    public static function call(): mixed
    {
        return PackagistCache::get();
    }
}
