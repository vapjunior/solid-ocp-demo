<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Product;
use App\Domain\ProductRepository;

/**
 * SRP: orquestra o CRUD + cálculo de preço final.
 * OCP: VIOLAÇÃO na finalPrice() (precificação por categoria via if/elseif).
 */
final class ProductService
{
    public function __construct(private ProductRepository $repo) {}

    public function create(string $name, string $category, float $basePrice): Product
    {
        $p = new Product(null, trim($name), strtolower(trim($category)), $basePrice);
        return $this->repo->add($p);
    }

    public function update(int $id, string $name, string $category, float $basePrice): void
    {
        $current = $this->repo->find($id);
        if ($current === null) { return; }
        $updated = new Product($id, trim($name), strtolower(trim($category)), $basePrice);
        $this->repo->update($updated);
    }

    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }

    /** @return Product[] */
    public function list(): array
    {
        return $this->repo->all();
    }

    public function getById(int $id): ?Product
    {
        return $this->repo->find($id);
    }

    /**
     * VIOLAÇÃO DE OCP:
     * - Toda vez que uma categoria nova surgir, este método precisará ser modificado.
     * - Torna o serviço fechado para extensão.
     */
    public function finalPrice(Product $p): float
    {
        $c = $p->category();
        $base = $p->basePrice();

        if ($c === 'livro') {
            return $base; // isento
        } elseif ($c === 'eletronico') {
            return $base + ($base * 0.15); // imposto 15%
        } elseif ($c === 'alimento') {
            return $base + ($base * 0.08); // imposto 8%
        } else {
            // categoria desconhecida -> preço base
            return $base;
        }
    }
}
