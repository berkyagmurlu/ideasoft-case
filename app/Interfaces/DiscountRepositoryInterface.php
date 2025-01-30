<?php
namespace App\Interfaces;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Collection;

interface DiscountRepositoryInterface extends BaseRepositoryInterface
{
    public function findActive(): Collection;
    public function findByCategory(int $categoryId): Collection;
    public function findByType(string $type): Collection;
    public function findActiveByCategory(int $categoryId): Collection;
}
