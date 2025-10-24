<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Application\ProductService;
use App\Infra\FileProductRepository;

$file = __DIR__ . '/../storage/products.txt';
$service = new ProductService(new FileProductRepository($file));

$items = $service->list();
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <title>Produtos (CRUD + OCP)</title>
  <style>
    body{font-family:system-ui,Arial;margin:2rem}
    table{border-collapse:collapse;width:100%}
    th,td{border:1px solid #bbb;padding:.5rem;text-align:left}
    form.inline{display:inline}
  </style>
</head>
<body>
  <h1>Produtos</h1>

  <p><a href="create.php">Criar produto</a></p>

  <table>
    <thead>
      <tr><th>ID</th><th>Nome</th><th>Categoria</th><th>Preço base</th><th>Preço final</th><th>Ações</th></tr>
    </thead>
    <tbody>
    <?php foreach ($items as $p): ?>
      <tr>
        <td><?= htmlspecialchars((string)$p->id()) ?></td>
        <td><?= htmlspecialchars($p->name()) ?></td>
        <td><?= htmlspecialchars($p->category()) ?></td>
        <td><?= number_format($p->basePrice(), 2, ',', '.') ?></td>
        <td><?= number_format($service->finalPrice($p), 2, ',', '.') ?></td>
        <td>
          <a href="update.php?id=<?= (int)$p->id() ?>">Editar</a>
          <form class="inline" method="post" action="delete.php" onsubmit="return confirm('Excluir?')">
            <input type="hidden" name="id" value="<?= (int)$p->id() ?>">
            <button type="submit">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
