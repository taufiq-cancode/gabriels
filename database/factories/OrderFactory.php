<?php

namespace Database\Factories;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 5);
        $totalPrice = $product->price * $quantity;

        return [
            'order_id' => 'ORD' . $this->faker->unique()->numberBetween(100000, 999999),
            'user_id' => User::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
        ];
    }
}
