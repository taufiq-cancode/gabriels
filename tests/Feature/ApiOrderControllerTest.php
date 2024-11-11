<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test showing orders for an authenticated user.
     */
    public function test_show_orders_for_authenticated_user()
    {
        // Create a user and log in
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create orders for the user
        $orders = Order::factory()->count(3)->create(['user_id' => $user->id]);

        // Request the showOrders endpoint
        $response = $this->getJson('/api/orders');

        // Assert the response structure and status
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'orders' => ['data' => [['id', 'user_id', 'product_id', 'quantity', 'total_price']]]
                 ])
                 ->assertJson(['success' => true]);
    }

    /**
     * Test storing orders with unauthorized access.
     */
    public function test_store_orders_with_unauthorized_access()
    {
        // Create a non-admin user and log in
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user, 'api');

        // Prepare valid request data
        $requestData = [
            'product_id' => [1],
            'quantity' => [2],
            'user_id' => $user->id,
        ];

        // Send POST request to store orders
        $response = $this->postJson('/api/orders', $requestData);

        // Assert unauthorized access response
        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Unauthorized access',
                 ]);
    }

    /**
     * Test storing orders with valid data.
     */
    public function test_store_orders_with_valid_data()
    {
        // Create an admin user, products, and log in
        $admin = User::factory()->create(['role' => 'admin']);
        $products = Product::factory()->count(2)->create();
        $this->actingAs($admin, 'api');

        // Prepare valid request data
        $requestData = [
            'product_id' => $products->pluck('id')->toArray(),
            'quantity' => [2, 3],
            'user_id' => $admin->id,
        ];

        // Send POST request to store orders
        $response = $this->postJson('/api/orders', $requestData);

        // Assert creation response
        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Orders added successfully!',
                 ]);

        // Assert that orders were saved in the database
        $this->assertDatabaseCount('orders', 2);
        foreach ($products as $index => $product) {
            $this->assertDatabaseHas('orders', [
                'user_id' => $admin->id,
                'product_id' => $product->id,
                'quantity' => $requestData['quantity'][$index],
                'total_price' => $product->price * $requestData['quantity'][$index],
            ]);
        }
    }

    /**
     * Test storing orders with invalid data.
     */
    public function test_store_orders_with_invalid_data()
    {
        // Create an admin user and log in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'api');

        // Prepare invalid request data
        $requestData = [
            'product_id' => [999], // Non-existent product ID
            'quantity' => [-1],    // Invalid quantity
            'user_id' => 9999,     // Non-existent user ID
        ];

        // Send POST request to store orders
        $response = $this->postJson('/api/orders', $requestData);

        // Assert validation errors
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['product_id.0', 'quantity.0', 'user_id']);
    }
}
