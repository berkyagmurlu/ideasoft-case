<?php
namespace App\Services;

use App\Interfaces\DiscountServiceInterface;
use App\Interfaces\DiscountRepositoryInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Discount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DiscountService implements DiscountServiceInterface
{
    protected DiscountRepositoryInterface $discountRepository;

    public function __construct(DiscountRepositoryInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function getActiveDiscounts(): Collection
    {
        return $this->discountRepository->findActive();
    }

    public function calculateDiscounts(Order $order): array
    {
        $discounts = [];
        $user = $order->user;
        $items = $order->items;
        $totalAmount = $order->total_amount;

        // Get all active discounts
        $activeDiscounts = Discount::where('is_active', true)->get();

        foreach ($activeDiscounts as $discount) {
            $discountAmount = 0;

            switch ($discount->type) {
                case 'total_amount':
                    if ($totalAmount >= $discount->min_amount) {
                        $discountAmount = $totalAmount * ($discount->discount_rate / 100);
                        $discounts[] = [
                            'discount_name' => $discount->name,
                            'discount_amount' => $discountAmount
                        ];
                    }
                    break;

                case 'category_quantity':
                    $categoryItems = $items->where('product.category_id', $discount->category_id);
                    $categoryItemCount = $categoryItems->sum('quantity');

                    if ($categoryItemCount >= $discount->min_quantity) {
                        $freeItemsCount = floor($categoryItemCount / $discount->min_quantity) * $discount->free_items;
                        $cheapestItems = $categoryItems->sortBy('unit_price')->take($freeItemsCount);
                        $discountAmount = $cheapestItems->sum('unit_price');
                        $discounts[] = [
                            'discount_name' => $discount->name,
                            'discount_amount' => $discountAmount
                        ];
                    }
                    break;

                case 'category_multiple':
                    $categoryItems = $items->where('product.category_id', $discount->category_id);
                    $categoryTotal = $categoryItems->sum('total_price');

                    if ($categoryTotal > 0) {
                        $discountAmount = $categoryTotal * ($discount->discount_rate / 100);
                        $discounts[] = [
                            'discount_name' => $discount->name,
                            'discount_amount' => $discountAmount
                        ];
                    }
                    break;

                case 'user_revenue':
                    if ($user->revenue >= $discount->user_revenue_min) {
                        $discountAmount = $totalAmount * ($discount->discount_rate / 100);
                        $discounts[] = [
                            'discount_name' => $discount->name,
                            'discount_amount' => $discountAmount
                        ];
                    }
                    break;

                case 'membership_duration':
                    $membershipMonths = Carbon::parse($user->since)->diffInMonths(now());
                    if ($membershipMonths >= $discount->membership_months_min) {
                        $discountAmount = $totalAmount * ($discount->discount_rate / 100);
                        $discounts[] = [
                            'discount_name' => $discount->name,
                            'discount_amount' => $discountAmount
                        ];
                    }
                    break;
            }
        }

        return $discounts;
    }

    public function applyDiscounts(Order $order): void
    {
        $discounts = $this->calculateDiscounts($order);
        $totalDiscountAmount = collect($discounts)->sum('discount_amount');

        $order->discount_amount = $totalDiscountAmount;
        $order->final_amount = $order->total_amount - $totalDiscountAmount;
        $order->save();

        // Update user's total revenue
        $user = $order->user;
        $user->revenue += $order->final_amount;
        $user->save();
    }

    public function calculateOrderDiscounts(Order $order): array
    {
        $discounts = [
            'discounts' => [],
            'total_discount' => 0
        ];

        // Get all active discounts
        $activeDiscounts = $this->discountRepository->findActive();
        $totalAmount = $order->total_amount;
        $user = $order->user;
        $items = $order->items;

        foreach ($activeDiscounts as $discount) {
            $discountAmount = 0;

            switch ($discount->type) {
                case 'total_amount':
                    if ($totalAmount >= $discount->min_amount) {
                        $discountAmount = $totalAmount * ($discount->discount_rate / 100);
                        $discounts['discounts'][] = [
                            'discount_id' => $discount->id,
                            'name' => $discount->name,
                            'type' => $discount->type,
                            'amount' => $discountAmount
                        ];
                        $discounts['total_discount'] += $discountAmount;
                    }
                    break;

                case 'category_quantity':
                    $categoryItems = $items->where('product.category_id', $discount->category_id);
                    $categoryItemCount = $categoryItems->sum('quantity');

                    if ($categoryItemCount >= $discount->min_quantity) {
                        $freeItemsCount = floor($categoryItemCount / $discount->min_quantity) * $discount->free_items;
                        $cheapestItems = $categoryItems->sortBy('unit_price')->take($freeItemsCount);
                        $discountAmount = $cheapestItems->sum('unit_price');
                        $discounts['discounts'][] = [
                            'discount_id' => $discount->id,
                            'name' => $discount->name,
                            'type' => $discount->type,
                            'amount' => $discountAmount
                        ];
                        $discounts['total_discount'] += $discountAmount;
                    }
                    break;

                case 'category_multiple':
                    $categoryItems = $items->where('product.category_id', $discount->category_id);
                    $categoryTotal = $categoryItems->sum('total_price');

                    if ($categoryTotal > 0) {
                        $discountAmount = $categoryTotal * ($discount->discount_rate / 100);
                        $discounts['discounts'][] = [
                            'discount_id' => $discount->id,
                            'name' => $discount->name,
                            'type' => $discount->type,
                            'amount' => $discountAmount
                        ];
                        $discounts['total_discount'] += $discountAmount;
                    }
                    break;

                case 'user_revenue':
                    if ($user->revenue >= $discount->user_revenue_min) {
                        $discountAmount = $totalAmount * ($discount->discount_rate / 100);
                        $discounts['discounts'][] = [
                            'discount_id' => $discount->id,
                            'name' => $discount->name,
                            'type' => $discount->type,
                            'amount' => $discountAmount
                        ];
                        $discounts['total_discount'] += $discountAmount;
                    }
                    break;

                case 'membership_duration':
                    $membershipMonths = Carbon::parse($user->since)->diffInMonths(now());
                    if ($membershipMonths >= $discount->membership_months_min) {
                        $discountAmount = $totalAmount * ($discount->discount_rate / 100);
                        $discounts['discounts'][] = [
                            'discount_id' => $discount->id,
                            'name' => $discount->name,
                            'type' => $discount->type,
                            'amount' => $discountAmount
                        ];
                        $discounts['total_discount'] += $discountAmount;
                    }
                    break;
            }
        }

        // Add order totals to response
        $discounts['total_amount'] = $totalAmount;
        $discounts['final_amount'] = $totalAmount - $discounts['total_discount'];
        $discounts['discount_amount'] = $discounts['total_discount'];

        return $discounts;
    }

    public function calculateCategoryDiscounts(int $categoryId, array $items): array
    {
        $discounts = [
            'discounts' => [],
            'total_discount' => 0
        ];

        $activeDiscounts = $this->discountRepository->findActiveByCategory($categoryId);

        foreach ($activeDiscounts as $discount) {
            switch ($discount->type) {
                case 'category_quantity':
                    // Buy X get Y free
                    foreach ($items as $item) {
                        if ($item['quantity'] >= $discount->min_quantity) {
                            $freeItems = floor($item['quantity'] / $discount->min_quantity) * $discount->free_items;
                            $discountAmount = $freeItems * $item['unit_price'];

                            $discounts['discounts'][] = [
                                'type' => 'category_quantity',
                                'category_id' => $categoryId,
                                'amount' => $discountAmount
                            ];
                            $discounts['total_discount'] += $discountAmount;
                        }
                    }
                    break;

                case 'category_multiple':
                    // Buy multiple items, get discount on cheapest
                    if (count($items) >= 2) {
                        $cheapestItem = null;
                        foreach ($items as $item) {
                            if ($cheapestItem === null || $item['unit_price'] < $cheapestItem['unit_price']) {
                                $cheapestItem = $item;
                            }
                        }

                        $discountAmount = $cheapestItem['unit_price'] * ($discount->discount_rate / 100);
                        $discounts['discounts'][] = [
                            'type' => 'category_multiple',
                            'category_id' => $categoryId,
                            'amount' => $discountAmount
                        ];
                        $discounts['total_discount'] += $discountAmount;
                    }
                    break;
            }
        }

        return $discounts;
    }

    public function calculateTotalAmountDiscount(float $totalAmount): float
    {
        $discounts = $this->discountRepository->findByType('total_amount');

        foreach ($discounts as $discount) {
            if ($discount->is_active && $totalAmount >= $discount->min_amount) {
                return $totalAmount * ($discount->discount_rate / 100);
            }
        }

        return 0;
    }

    public function deleteDiscount(int $id): bool
    {
        $discount = $this->discountRepository->find($id);
        
        if (!$discount) {
            return false;
        }
        
        DB::transaction(function () use ($discount) {
            // İndirim kaydını sil
            $discount->delete();
        });
        
        return true;
    }
}
