<?php

declare(strict_types=1);

namespace App\Domain;

final class Product
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $category,
        private float $basePrice
    ) {}

    public function id(): ?int { return $this->id; }
    public function withId(int $id): self { $c = clone $this; $c->id = $id; return $c; }

    public function name(): string { return $this->name; }
    public function category(): string { return $this->category; }
    public function basePrice(): float { return $this->basePrice; }

    /** @return array{id:int|null,name:string,category:string,basePrice:float} */
    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'category'  => $this->category,
            'basePrice' => $this->basePrice,
        ];
    }

    /** @param array{id:int|null,name:string,category:string,basePrice:float} $row */
    public static function fromArray(array $row): self
    {
        return new self(
            $row['id'] === null ? null : (int)$row['id'],
            (string)$row['name'],
            (string)$row['category'],
            (float)$row['basePrice']
        );
    }
}
