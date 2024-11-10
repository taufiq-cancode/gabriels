<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ApiOrderController extends Controller
{
    public function showOrders()
    {
        $user = auth()->user();

        $orders = Order::where('user_id', $user->id)
                        ->with('product')
                        ->orderBy('created_at', 'desc')
                        ->paginate(5);

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ], 200);
    }

    public function store(Request $request)
    {        
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validated = $request->validate([
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'integer|min:1',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $ordersData = [];

        foreach ($validated['product_id'] as $index => $productId) {
            $product = Product::findOrFail($productId);

            $quantity = $validated['quantity'][$index];
            $totalPrice = $product->price * $quantity;

            do {
                $orderId = 'ORD' . rand(100000, 999999);
            } while (Order::where('order_id', $orderId)->exists());

            $ordersData[] = [
                'order_id' => $orderId,
                'user_id' => $validated['user_id'],
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Order::insert($ordersData);

        return response()->json([
            'success' => true,
            'message' => 'Orders added successfully!',
            'orders' => $ordersData
        ], 201);
    }
}
