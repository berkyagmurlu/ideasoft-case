<?php
namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCategory(int $categoryId): Collection;
    public function updateStock(int $id, int $quantity): bool;
    public function checkStock(int $id, int $quantity): bool;
    public function findWithCategory(int $id): ?Product;
}
