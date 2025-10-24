<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Application\ProductService;
use App\Infra\FileProductRepository;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Método não permitido';
    exit;
}

$file = __DIR__ . '/../storage/products.txt';
$service = new ProductService(new FileProductRepository($file));

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $service->delete($id);
}
header('Location: index.php');
