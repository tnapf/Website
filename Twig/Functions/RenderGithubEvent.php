<?php

namespace Twig\Functions;

use function Common\renderHtml;

class RenderGithubEvent extends TwigFunction
{
    public static function getName(): string
    {
        return "render_github_event";
    }

    public static function call(array $event = []): mixed
    {
        if (empty($event)) {
            return "";
        }

        $part = "";

        if ($event["type"] === "CreateEvent" && $event["payload"]["ref_type"] === "branch") {
            $part = "parts.events.create.branch";
        }

        if ($event["type"] === "DeleteEvent" && $event["payload"]["ref_type"] === "branch") {
            $part = "parts.events.delete.branch";
        }

        if ($event["type"] === "PushEvent") {
            $part = "parts.events.push";
        }

        if ($event["type"] === "ReleaseEvent") {
            $part = "parts.events.release";
        }

        if ($event["type"] === "PullRequestEvent" && $event["payload"]["action"] === "closed") {
            $part = "parts.events.pull_request.close";
        }

        if ($event["type"] === "PullRequestEvent" && $event["payload"]["action"] === "opened") {
            $part = "parts.events.pull_request.open";
        }

        return empty($part) ? "" : renderHtml($part, compact("event"));
    }
}
