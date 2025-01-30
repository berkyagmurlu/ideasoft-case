<?php
namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface DiscountServiceInterface
{
    public function getActiveDiscounts(): Collection;
    public function calculateOrderDiscounts(Order $order): array;
    public function calculateCategoryDiscounts(int $categoryId, array $items): array;
    public function calculateTotalAmountDiscount(float $totalAmount): float;
}
