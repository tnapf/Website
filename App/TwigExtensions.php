<?php

use Twig\Functions\Entrypoint;
use Twig\Functions\FormatDate;
use Twig\Functions\Github;
use Twig\Functions\Render;
use Twig\Functions\RenderGithubEvent;

Entrypoint::add();
Render::add();
Github::add();
FormatDate::add();
RenderGithubEvent::add();
