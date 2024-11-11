<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Set up required models and data
        $this->user = User::factory()->create();
        $this->products = Product::factory()->count(3)->create();
    }

    public function test_it_shows_orders_for_authenticated_user()
    {
        // Create an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create orders associated with this user
        Order::factory()->count(3)->for($user)->create();

        // Perform a GET request to show orders (non-AJAX)
        $response = $this->get(route('orders'));

        // Assert the view is correct and contains expected data
        $response->assertStatus(200)
                ->assertViewIs('orders.index')  // Ensure the returned view is 'orders.index'
                ->assertViewHas('orders')       // Ensure 'orders' is passed to the view
                ->assertViewHas('products')     // Ensure 'products' is passed to the view
                ->assertViewHas('users');       // Ensure 'users' is passed to the view
    }

    public function test_it_shows_orders_for_authenticated_user_via_ajax()
    {
        // Create an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create orders associated with this user
        Order::factory()->count(3)->for($user)->create();

        // Perform a GET request to show orders with AJAX headers
        $response = $this->getJson(route('orders'), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assert JSON structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'orders' => [
                    '*' => [
                        'order_id',
                        'user_id',
                        'product_id',
                        'quantity',
                        'total_price',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'pagination'
            ]);
    }


    public function test_it_stores_orders_successfully_with_valid_data()
    {    
        $user = User::factory()->create();
        $product = Product::factory()->create();
    
        $this->actingAs($user);
    
        $data = [
            'user_id' => $user->id,
            'product_id' => [$product->id],
            'quantity' => [1],
        ];
    
        $response = $this->withSession(['_token' => csrf_token()])
        ->post(route('admin.orders.store'), $data);

        $response->assertSessionHasNoErrors(); // Check for validation errors
        $response->assertRedirect(); // Check if it redirects correctly
    
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
    

    public function test_it_fails_to_store_orders_with_invalid_product_id()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $data = [
            'user_id' => $user->id,
            'product_id' => [9999],  // Invalid product ID
            'quantity' => [1],
        ];

        $response = $this->post(route('admin.orders.store'), $data);

        $response->assertSessionHasErrors(['product_id.0']);
    }

    public function test_it_fails_to_store_orders_with_invalid_quantity()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user);

        $data = [
            'user_id' => $user->id,
            'product_id' => [$product->id],
            'quantity' => [0],  // Invalid quantity (must be at least 1)
        ];

        $response = $this->post(route('admin.orders.store'), $data);

        $response->assertSessionHasErrors(['quantity.0']);
    }

    public function test_it_fails_to_store_orders_with_missing_user_id()
    {
        $product = Product::factory()->create();
    
        $data = [
            'product_id' => [$product->id],
            'quantity' => [1],
        ];
    
        $response = $this->post(route('admin.orders.store'), $data);
    
        $response->assertSessionHasErrors(['user_id']);
    }
    
}
