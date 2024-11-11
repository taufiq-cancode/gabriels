<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class ApiUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that non-admin users are forbidden from accessing the index method.
     */
    public function test_index_forbidden_for_non_admin()
    {
        // Create a non-admin user and log in
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user, 'api');

        // Request the index route
        $response = $this->getJson(route('api.users.index'));

        // Assert the response is a 403 forbidden status
        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Unauthorized access'
                 ]);
    }

    /**
     * Test that the index method returns a JSON response for admin users.
     */
    public function test_index_returns_json_for_admin_user()
    {
        // Create an admin user and log in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'api');

        // Create some users with orders
        User::factory()->count(3)->create();

        // Make a request to the index route
        $response = $this->getJson(route('api.users.index'));

        // Assert the response structure and status
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'users' => ['data' => [['id', 'name', 'email', 'created_at', 'orders_count']]]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);
    }

    /**
     * Test that non-admin users are forbidden from accessing the show method.
     */
    public function test_show_forbidden_for_non_admin()
    {
        // Create a non-admin user and log in
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user, 'api');

        // Create a user to view
        $otherUser = User::factory()->create();

        // Request the show route
        $response = $this->getJson(route('api.users.show', $otherUser->id));

        // Assert the response is a 403 forbidden status
        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Unauthorized access'
                 ]);
    }

    /**
     * Test that the show method returns a JSON response with user and orders data for admin users.
     */
    public function test_show_returns_json_for_admin_user()
    {
        // Create an admin user and log in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'api');

        // Create a user with orders
        $user = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);

        // Request the show route
        $response = $this->getJson(route('api.users.show', $user->id));

        // Assert the response structure and status
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'user' => ['id', 'name', 'email', 'created_at'],
                     'orders' => ['data' => [['id', 'product_id', 'quantity', 'total_price', 'created_at']]]
                 ])
                 ->assertJson([
                     'success' => true,
                     'user' => ['id' => $user->id]
                 ]);
    }

    /**
     * Test that the show method returns a 404 for a non-existent user.
     */
    public function test_show_returns_404_for_non_existent_user()
    {
        // Create an admin user and log in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'api');

        // Request the show route with a non-existent user ID
        $response = $this->getJson(route('api.users.show', 9999));

        // Assert a 404 status is returned
        $response->assertStatus(404);
    }
}
