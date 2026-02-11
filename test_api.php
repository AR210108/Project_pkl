<?php
/**
 * Quick test for API endpoints
 * Run: php test_api.php
 */

require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';

echo "=== Testing API Routes ===\n";
echo "Checking if routes are registered...\n\n";

// Load all routes
$router = $app->make('router');
$routes = $router->getRoutes();

echo "API Karyawan Routes:\n";
foreach ($routes as $route) {
    if (strpos($route->uri, 'api/karyawan') !== false) {
        echo "  - " . $route->methods[0] . " " . $route->uri . "\n";
    }
}

echo "\n✅ Route list generated.\n";
