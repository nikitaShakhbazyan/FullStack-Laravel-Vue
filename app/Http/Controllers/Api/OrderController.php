<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function index(Request $request): JsonResponse
    {
        if ($request->user()->isAdmin()) {
            $orders = $this->orderService->getAllOrders();
        } else {
            $orders = $this->orderService->getUserOrders($request->user());
        }

        return response()->json($orders);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder(
            $request->user(),
            $request->validated()['items']
        );

        return response()->json($order, 201);
    }

    public function show(Order $order): JsonResponse
    {
        $order->load('orderItems.product');

        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        $order = $this->orderService->updateOrderStatus($order, $request->status);

        return response()->json($order);
    }
}
