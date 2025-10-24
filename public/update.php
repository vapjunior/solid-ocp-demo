<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Application\ProductService;
use App\Infra\FileProductRepository;

$file = __DIR__ . '/../storage/products.txt';
$service = new ProductService(new FileProductRepository($file));

$id = (int)($_GET['id'] ?? 0);
$prod = $service->getById($id);
if ($prod === null) {
    http_response_code(404);
    echo 'Produto não encontrado';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = (string)($_POST['name'] ?? '');
    $cat  = (string)($_POST['category'] ?? '');
    $price= (float)($_POST['basePrice'] ?? 0);

    $service->update($id, $name, $cat, $price);
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head><meta charset="utf-8" /><title>Editar</title></head>
<body>
  <h1>Editar Produto</h1>
  <form method="post">
    <p><label>Nome: <input name="name" value="<?= htmlspecialchars($prod->name()) ?>" required></label></p>
    <p><label>Categoria: <input name="category" value="<?= htmlspecialchars($prod->category()) ?>" required></label></p>
    <p><label>Preço base: <input type="number" step="0.01" name="basePrice" value="<?= htmlspecialchars((string)$prod->basePrice()) ?>" required></label></p>
    <p><button type="submit">Salvar</button> <a href="index.php">Voltar</a></p>
  </form>
</body>
</html>
