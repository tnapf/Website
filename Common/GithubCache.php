<?php

namespace Common;

use Github\Client;
use JsonSerializable;

use function Common\env;

class GithubCache implements JsonSerializable {
    public readonly Client $client;
    public array $repositories;
    public array $members;
    public array $events;
    private int $expiration;
    private static self $instance;
    private const CACHE_FILE = __ROOT__ . "/Cache/github.json";
    private const ORG = "tnapf";
    private const EXPIRATION = 86400;

    public function __construct() {
        $this->client = env()->github;

        if (!file_exists(self::CACHE_FILE)) {
            $this->repositories = $this->client->organization()->repositories(self::ORG);
            $this->members = $this->client->organization()->members()->all(self::ORG);
            $this->events = $this->client->user()->events(self::ORG);
            return;
        }

        $cache = json_decode(file_get_contents(self::CACHE_FILE), true);

        if ($cache['expiration'] > time()) {
            $this->repositories = $cache['repositories'];
            $this->members = $cache['members'];
            $this->events = $cache['events'];
            $this->expiration = $cache['expiration'];
        } else {
            unlink(self::CACHE_FILE);
        }
    }

    public static function get() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function jsonSerialize() {
        return [
            "repositories" => $this->repositories,
            "members" => $this->members,
            "events" => $this->events,
            "expiration" => $this->expiration ?? time() + self::EXPIRATION
        ];
    }

    public function save() {
        file_put_contents(self::CACHE_FILE, json_encode($this, JSON_PRETTY_PRINT));
    }

    public function __destruct() {
        $this->save();
    }
}