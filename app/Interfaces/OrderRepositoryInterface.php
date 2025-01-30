<?php
namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUser(int $userId): Collection;
    public function findWithItems(int $id): ?Order;
    public function createWithItems(array $data, array $items): Order;
    public function updateStatus(int $id, string $status): bool;
}
