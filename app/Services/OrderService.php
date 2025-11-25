<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(User $user, array $items): Order
    {
        return DB::transaction(function () use ($user, $items) {
            $totalAmount = 0;
            $orderItems = [];

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $itemPrice = $product->price * $item['quantity'];
                $totalAmount += $itemPrice;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => $totalAmount,
            ]);

            foreach ($orderItems as $orderItem) {
                $order->orderItems()->create($orderItem);
            }

            OrderCreated::dispatch($order);

            return $order->load('orderItems.product');
        });
    }

    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);
        return $order;
    }

    public function getUserOrders(User $user)
    {
        return Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->latest()
            ->get();
    }

    public function getAllOrders()
    {
        return Order::with(['user', 'orderItems.product'])
            ->latest()
            ->get();
    }
}
