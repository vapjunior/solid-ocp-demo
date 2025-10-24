<?php

declare(strict_types=1);

namespace App\Domain;

interface ProductRepository
{
    public function add(Product $p): Product; // retorna com ID
    public function update(Product $p): void;
    public function delete(int $id): void;
    public function find(int $id): ?Product;

    /** @return Product[] */
    public function all(): array;
}
