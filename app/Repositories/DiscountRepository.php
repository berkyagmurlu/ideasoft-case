<?php
namespace App\Repositories;

use App\Interfaces\DiscountRepositoryInterface;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Collection;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }

    public function findActive(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function findByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    public function findByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }

    public function findActiveByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)
            ->where('is_active', true)
            ->get();
    }
}
