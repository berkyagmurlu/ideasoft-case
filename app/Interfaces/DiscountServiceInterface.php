<?php
namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface DiscountServiceInterface
{
    public function getActiveDiscounts(): Collection;
    public function calculateDiscounts(Order $order): array;
    public function applyDiscounts(Order $order): void;
    public function calculateOrderDiscounts(Order $order): array;
    public function calculateCategoryDiscounts(int $categoryId, array $items): array;
    public function calculateTotalAmountDiscount(float $totalAmount): float;
    public function deleteDiscount(int $id): bool;
}
