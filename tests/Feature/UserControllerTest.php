<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that non-admin users are redirected from the index method.
     */
    public function test_index_redirects_non_admin_user()
    {
        // Create a non-admin user and log in
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        // Request the index route
        $response = $this->get(route('users.index'));

        // Assert that non-admins are redirected to the orders route
        $response->assertRedirect(route('orders'));
    }

    /**
     * Test that the index method returns a JSON response for AJAX requests.
     */
    public function test_index_returns_json_for_ajax()
    {
        // Create an admin user and log in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create some users
        User::factory()->count(3)->create();

        // Make an AJAX request to the index route
        $response = $this->getJson(route('users.index'));

        // Assert the response structure and status
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'users' => [['id', 'name', 'email', 'created_at']],
                     'pagination'
                 ]);
    }

    /**
     * Test that the index method returns the correct view for admin users.
     */
    public function test_index_returns_view_for_admin_user()
    {
        // Create an admin user and log in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create some users
        User::factory()->count(3)->create();

        // Request the index route
        $response = $this->get(route('users.index'));

        // Assert the correct view is returned with users data
        $response->assertStatus(200)
                 ->assertViewIs('users.index')
                 ->assertViewHas('users');
    }

    /**
     * Test that the show method returns a JSON response for AJAX requests.
     */
    public function test_show_returns_json_for_ajax()
    {
        // Create a user with orders and products
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();
        foreach ($products as $product) {
            Order::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);
        }

        // Make an AJAX request to the show route
        $response = $this->getJson(route('users.show', $user->id));

        // Assert the response structure and status
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'orders' => [['id', 'product_id', 'quantity', 'total_price', 'created_at']],
                     'pagination'
                 ]);
    }

    /**
     * Test that the show method returns the correct view with user data.
     */
    public function test_show_returns_view_with_user_data()
    {
        // Create a user with orders and products
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();
        foreach ($products as $product) {
            Order::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);
        }

        // Request the show route
        $response = $this->get(route('users.show', $user->id));

        // Assert the correct view is returned with user, products, and orders data
        $response->assertStatus(200)
                 ->assertViewIs('users.show')
                 ->assertViewHasAll(['user', 'products', 'orders']);
    }

    /**
     * Test that the show method returns a 404 for a non-existent user.
     */
    public function test_show_returns_404_for_non_existent_user()
    {
        // Create an admin user and log in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Request the show route with a non-existent user ID
        $response = $this->get(route('users.show', 9999));

        // Assert a 404 status is returned
        $response->assertStatus(404);
    }
}
