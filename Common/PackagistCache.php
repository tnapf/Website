<?php

namespace Common;

use Exception;
use JsonSerializable;

class PackagistCache implements JsonSerializable {
    public array $packages;
    private int $expiration;
    private const CACHE_FILE = __ROOT__ . "/Cache/packagist.json";
    private const PACKAGIST_URL = "https://packagist.org/packages/list.json?vendor=tnapf";
    private const EXPIRATION = 86400;
    private const PACKAGE_URL = "https://packagist.org/packages/%s/%s.json";
    private static self $instance;

    public function __construct() {
        if (isset(self::$instance)) {
            throw new Exception("PackagistCache has already been initialized");
        }

        if (file_exists(self::CACHE_FILE)) {
            $cache = json_decode(file_get_contents(self::CACHE_FILE), true);

            if ($cache['expiration'] < time()) {
                unlink(self::CACHE_FILE);
            } else {
                $this->packages = $cache['packages'];
                $this->expiration = $cache['expiration'];
                return;
            }
        }

        $packages = json_decode(file_get_contents(self::PACKAGIST_URL), true)['packageNames'];
        $this->packages = array_map(fn($package) => $this->getPackage(...explode("/", $package)), $packages);
    }

    public function jsonSerialize() {
        return [
            "packages" => $this->packages,
            "expiration" => $this->expiration ?? time() + self::EXPIRATION
        ];
    }

    public function getPackage(string $vendor, string $package): array
    {
        $url = sprintf(self::PACKAGE_URL, $vendor, $package);
        $data = json_decode(file_get_contents($url), true);
        return $data['package'];
    }

    public function save() {
        file_put_contents(self::CACHE_FILE, json_encode($this, JSON_PRETTY_PRINT));
    }

    public static function get() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getLatestVersion(array $versions): ?string
    {
        foreach (array_keys($versions) as $version) {
            if (strpos($version, "dev") !== false) {
                continue;
            }

            return $version;
        }
    }

    public function __destruct() {
        $this->save();
    }
}