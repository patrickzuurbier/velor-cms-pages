<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Velor\Pages\Http\Controllers\PageController;
use Velor\Pages\Http\Controllers\ParagraphController;

/**
 * @var Router $router
 */
$router->resources(['pages' => PageController::class]);
$router->resources(['pages.paragraphs' => ParagraphController::class]);
