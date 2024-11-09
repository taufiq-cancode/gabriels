<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    public function showOrders()
    {
        $user = auth()->user();
        
        $orders = Order::where('user_id', $user->id)
                        ->with('user', 'product')
                        ->orderby('created_at', 'desc')
                        ->paginate(5);;

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
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
    
        return redirect()->back()->with('success', 'Orders added successfully!');
    }
}
