<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

use Illuminate\Http\Request;
use App\Http\Controllers\InvoiceController;

$request = Request::create('/admin/api/invoices?ajax=1', 'GET');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Accept', 'application/json');

$controller = new InvoiceController();
$response = $controller->index($request);

if (is_object($response) && method_exists($response, 'getContent')) {
    echo $response->getContent();
} else {
    var_export($response);
}
