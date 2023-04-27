<?php

namespace Twig\Functions;

use Common\GithubCache;

class Github extends TwigFunction
{
    public static function getName(): string
    {
        return "github";
    }

    public static function call(): mixed
    {
        return GithubCache::get();
    }
}
