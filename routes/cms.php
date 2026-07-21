<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use App\Http\Controllers\Cms\PageController;
use App\Http\Controllers\Cms\ParagraphController;

/**
 * @var Router $router
 */
$router->resources(['pages' => PageController::class]);
$router->resources(['pages.paragraphs' => ParagraphController::class]);
