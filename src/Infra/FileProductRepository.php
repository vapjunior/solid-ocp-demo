<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\Product;
use App\Domain\ProductRepository;
use RuntimeException;

final class FileProductRepository implements ProductRepository
{
    public function __construct(private string $filePath)
    {
        $dir = \dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($this->filePath)) {
            touch($this->filePath);
        }
    }

    /** @return Product[] */
    public function all(): array
    {
        $lines = @file($this->filePath, FILE_IGNORE_NEW_LINES) ?: [];
        $items = [];
        foreach ($lines as $line) {
            if (trim($line) === '') { continue; }
            $row = json_decode($line, true);
            if (!is_array($row)) { continue; }
            $items[] = Product::fromArray([
                'id'        => $row['id'] ?? null,
                'name'      => $row['name'] ?? '',
                'category'  => $row['category'] ?? '',
                'basePrice' => (float)($row['basePrice'] ?? 0.0),
            ]);
        }
        return $items;
    }

    public function find(int $id): ?Product
    {
        foreach ($this->all() as $p) {
            if ($p->id() === $id) { return $p; }
        }
        return null;
    }

    public function add(Product $p): Product
    {
        $all = $this->all();
        $newId = $this->nextId($all);
        $withId = $p->withId($newId);
        $this->append($withId);
        return $withId;
    }

    public function update(Product $p): void
    {
        if ($p->id() === null) {
            throw new RuntimeException('Cannot update product without ID');
        }
        $all = $this->all();
        $out = [];
        foreach ($all as $item) {
            if ($item->id() === $p->id()) {
                $out[] = $p;
            } else {
                $out[] = $item;
            }
        }
        $this->rewrite($out);
    }

    public function delete(int $id): void
    {
        $all = $this->all();
        $out = array_filter($all, fn(Product $p) => $p->id() !== $id);
        $this->rewrite(array_values($out));
    }

    /** @param Product[] $items */
    private function rewrite(array $items): void
    {
        $fh = fopen($this->filePath, 'w');
        if ($fh === false) { throw new RuntimeException('Cannot open file'); }
        foreach ($items as $p) {
            fwrite($fh, json_encode($p->toArray(), JSON_UNESCAPED_UNICODE) . PHP_EOL);
        }
        fclose($fh);
    }

    private function append(Product $p): void
    {
        file_put_contents(
            $this->filePath,
            json_encode($p->toArray(), JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND
        );
    }

    /** @param Product[] $all */
    private function nextId(array $all): int
    {
        $max = 0;
        foreach ($all as $p) {
            $max = max($max, (int)$p->id());
        }
        return $max + 1;
    }
}
