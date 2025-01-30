<?php
namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithProducts(int $id): ?Category;
    public function getAllWithProducts(): Collection;
}
