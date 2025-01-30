<?php
namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    public function updateStock(int $id, int $quantity): bool
    {
        return $this->model->where('id', $id)
            ->where('stock', '>=', abs($quantity))
            ->update(['stock' => \DB::raw("stock - " . abs($quantity))]);
    }

    public function checkStock(int $id, int $quantity): bool
    {
        return $this->model->where('id', $id)
            ->where('stock', '>=', $quantity)
            ->exists();
    }

    public function findWithCategory(int $id): ?Product
    {
        return $this->model->with('category')->find($id);
    }
}
