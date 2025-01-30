<?php
namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface
{
    public function createOrder(array $data, array $items): Order;
    public function calculateDiscounts(Order $order): array;
    public function getUserOrders(int $userId): Collection;
    public function getOrderDetails(int $orderId): ?Order;
    public function validateStock(array $items): bool;
    public function updateStatus(int $id, string $status): ?Order;
}
