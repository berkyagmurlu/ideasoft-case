<?php
namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function findWithProducts(int $id): ?Category
    {
        return $this->model->with('products')->find($id);
    }

    public function getAllWithProducts(): Collection
    {
        return $this->model->with('products')->get();
    }
}
