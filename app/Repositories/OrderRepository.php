<?php
namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function findByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function findWithItems(int $id): ?Order
    {
        return $this->model->with(['items.product', 'user'])->find($id);
    }

    public function createWithItems(array $data, array $items): Order
    {
        return DB::transaction(function () use ($data, $items) {
            $order = $this->create($data);
            $order->items()->createMany($items);
            return $order->fresh(['items']);
        });
    }

    public function updateStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }
}
