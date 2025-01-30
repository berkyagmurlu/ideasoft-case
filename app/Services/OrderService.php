<?php
namespace App\Services;

use App\Interfaces\OrderServiceInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\DiscountServiceInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    protected OrderRepositoryInterface $orderRepository;
    protected ProductRepositoryInterface $productRepository;
    protected DiscountServiceInterface $discountService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        DiscountServiceInterface $discountService
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->discountService = $discountService;
    }

    public function createOrder(array $data, array $items): Order
    {
        return DB::transaction(function () use ($data, $items) {
            // Calculate initial totals
            $totalAmount = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $product = $this->productRepository->find($item['product_id']);
                $totalPrice = $product->price * $item['quantity'];

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $totalPrice,
                    'discount_amount' => 0,
                    'final_price' => $totalPrice
                ];

                $totalAmount += $totalPrice;

                // Update product stock
                $this->productRepository->updateStock($product->id, $item['quantity']);
            }

            // Create order
            $order = $this->orderRepository->createWithItems([
                'user_id' => $data['user_id'],
                'total_amount' => $totalAmount,
                'discount_amount' => 0,
                'final_amount' => $totalAmount,
                'status' => 'pending'
            ], $orderItems);

            $this->applyDiscounts($order);

            return $this->orderRepository->findWithItems($order->id);
        });
    }

    public function calculateDiscounts(Order $order): array
    {
        return $this->discountService->calculateOrderDiscounts($order);
    }

    public function applyDiscounts(Order $order): void
    {
        $this->discountService->applyDiscounts($order);
    }

    public function getUserOrders(int $userId): Collection
    {
        return $this->orderRepository->findByUser($userId);
    }

    public function getOrderDetails(int $orderId): ?Order
    {
        return $this->orderRepository->findWithItems($orderId);
    }

    public function validateStock(array $items): bool
    {
        foreach ($items as $item) {
            if (!$this->productRepository->checkStock($item['product_id'], $item['quantity'])) {
                return false;
            }
        }
        return true;
    }

    public function updateStatus(int $id, string $status): ?Order
    {
        return $this->orderRepository->updateStatus($id, $status);
    }
}
