<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\StoreDiscountRequest;
use App\Interfaces\DiscountRepositoryInterface;
use App\Interfaces\DiscountServiceInterface;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Discounts",
 *     description="API Endpoints for discount management"
 * )
 */
final class DiscountController extends Controller
{
    protected DiscountServiceInterface $discountService;
    protected DiscountRepositoryInterface $discountRepository;
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(DiscountServiceInterface $discountService, DiscountRepositoryInterface $discountRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->discountService = $discountService;
        $this->discountRepository = $discountRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/discounts",
     *     summary="List all discounts",
     *     description="Get a list of all discounts",
     *     operationId="discountIndex",
     *     tags={"Discounts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of discounts",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Summer Sale"),
     *                     @OA\Property(property="type", type="string", enum={"total_amount", "category_quantity", "category_multiple", "user_revenue", "membership_duration"}, example="total_amount"),
     *                     @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *                     @OA\Property(property="min_amount", type="number", format="float", nullable=true, example=1000),
     *                     @OA\Property(property="min_quantity", type="integer", nullable=true, example=3),
     *                     @OA\Property(property="discount_rate", type="number", format="float", nullable=true, example=10),
     *                     @OA\Property(property="free_items", type="integer", nullable=true, example=1),
     *                     @OA\Property(property="is_active", type="boolean", example=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->discountService->getActiveDiscounts()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/discounts",
     *     summary="Create a new discount",
     *     description="Create a new discount with the provided data",
     *     operationId="discountStore",
     *     tags={"Discounts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type"},
     *             @OA\Property(property="name", type="string", example="Summer Sale"),
     *             @OA\Property(property="type", type="string", enum={"total_amount", "category_quantity", "category_multiple"}, example="total_amount"),
     *             @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="min_amount", type="number", format="float", nullable=true, example=1000),
     *             @OA\Property(property="min_quantity", type="integer", nullable=true, example=3),
     *             @OA\Property(property="discount_rate", type="number", format="float", nullable=true, example=10),
     *             @OA\Property(property="free_items", type="integer", nullable=true, example=1),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Discount created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Summer Sale"),
     *                 @OA\Property(property="type", type="string", enum={"total_amount", "category_quantity", "category_multiple"}, example="total_amount"),
     *                 @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="min_amount", type="number", format="float", nullable=true, example=1000),
     *                 @OA\Property(property="min_quantity", type="integer", nullable=true, example=3),
     *                 @OA\Property(property="discount_rate", type="number", format="float", nullable=true, example=10),
     *                 @OA\Property(property="free_items", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $discount = $this->discountRepository->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $discount
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/discounts/{id}",
     *     summary="Get discount details",
     *     description="Get detailed information about a specific discount",
     *     operationId="discountShow",
     *     tags={"Discounts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of discount to return",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discount details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Summer Sale"),
     *                 @OA\Property(property="type", type="string", enum={"total_amount", "category_quantity", "category_multiple"}, example="total_amount"),
     *                 @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="min_amount", type="number", format="float", nullable=true, example=1000),
     *                 @OA\Property(property="min_quantity", type="integer", nullable=true, example=3),
     *                 @OA\Property(property="discount_rate", type="number", format="float", nullable=true, example=10),
     *                 @OA\Property(property="free_items", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Discount not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Discount not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $discount = $this->discountRepository->find($id);

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Discount not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $discount
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/discounts/{id}",
     *     summary="Update discount",
     *     description="Update an existing discount",
     *     operationId="discountUpdate",
     *     tags={"Discounts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of discount to update",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type"},
     *             @OA\Property(property="name", type="string", example="Summer Sale"),
     *             @OA\Property(property="type", type="string", enum={"total_amount", "category_quantity", "category_multiple"}, example="total_amount"),
     *             @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="min_amount", type="number", format="float", nullable=true, example=1000),
     *             @OA\Property(property="min_quantity", type="integer", nullable=true, example=3),
     *             @OA\Property(property="discount_rate", type="number", format="float", nullable=true, example=10),
     *             @OA\Property(property="free_items", type="integer", nullable=true, example=1),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discount updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Summer Sale"),
     *                 @OA\Property(property="type", type="string", enum={"total_amount", "category_quantity", "category_multiple"}, example="total_amount"),
     *                 @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="min_amount", type="number", format="float", nullable=true, example=1000),
     *                 @OA\Property(property="min_quantity", type="integer", nullable=true, example=3),
     *                 @OA\Property(property="discount_rate", type="number", format="float", nullable=true, example=10),
     *                 @OA\Property(property="free_items", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Discount not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Discount not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function update(StoreDiscountRequest $request, int $id): JsonResponse
    {
        $discount = $this->discountRepository->find($id);

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Discount not found'
            ], 404);
        }

        $updated = $this->discountRepository->update($id, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $updated
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/discounts/{id}",
     *     summary="Delete discount",
     *     description="Delete an existing discount",
     *     operationId="discountDestroy",
     *     tags={"Discounts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of discount to delete",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discount deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Discount deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Discount not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Discount not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $discount = $this->discountRepository->find($id);

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Discount not found'
            ], 404);
        }

        $this->discountService->deleteDiscount($id);

        return response()->json([
            'success' => true,
            'message' => 'Discount deleted successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders/{order}/discounts",
     *     summary="Calculate order discounts",
     *     description="Calculate applicable discounts for a specific order",
     *     operationId="calculateDiscount",
     *     tags={"Discounts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="ID of order to calculate discounts for",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discount calculations",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total_amount", type="number", format="float", example=1999.98),
     *                 @OA\Property(property="discount_amount", type="number", format="float", example=199.99),
     *                 @OA\Property(property="final_amount", type="number", format="float", example=1799.99),
     *                 @OA\Property(property="applied_discounts", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="discount_id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Summer Sale"),
     *                         @OA\Property(property="type", type="string", example="total_amount"),
     *                         @OA\Property(property="amount", type="number", format="float", example=199.99)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function calculateDiscount(int $order): JsonResponse
    {
        $model = $this->orderRepository->find($order);

        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $discounts = $this->discountService->calculateOrderDiscounts($model);

        return response()->json([
            'success' => true,
            'data' => $discounts
        ]);
    }
} 