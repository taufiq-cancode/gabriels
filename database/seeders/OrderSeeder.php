<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;


class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        $statuses = ['Pending', 'Completed', 'Shipped', 'Cancelled', 'Processing'];

        foreach ($users as $user) {
            foreach ($products->random(3) as $product) {
                $orderId = 'ORD' . rand(100000, 999999);

                while (Order::where('order_id', $orderId)->exists()) {
                    $orderId = 'ORD' . rand(100000, 999999);
                }

                $status = $statuses[array_rand($statuses)];

                Order::create([
                    'order_id' => $orderId,
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 5),
                    'total_price' => $product->price * rand(1, 5),
                    'status' => $status,
                ]);
            }
        }
    }
}
