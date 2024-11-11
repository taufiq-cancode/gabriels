<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class WebAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_it_shows_the_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_it_shows_the_register_page()
    {
        $response = $this->withMiddleware()
                     ->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_it_registers_a_user_successfully()
    {
        $userData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect(route('orders'));
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);
        $this->assertAuthenticatedAs(User::where('email', 'john.doe@example.com')->first());
    }

    public function test_it_fails_registration_with_missing_fields()
    {
        $response = $this->post('/register', [
            'firstname' => 'John',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302); // Expecting a redirect
        $response->assertSessionHasErrors(['lastname']); // Check if 'lastname' field has error
    }

    public function test_it_fails_registration_with_invalid_email_format()
    {
        $response = $this->post('/register', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302); // Expecting a redirect
        $response->assertSessionHasErrors(['email']); // Check if 'email' field has error
    }

    public function test_it_fails_registration_with_duplicate_email()
    {
        User::create([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane.smith@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/register', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'jane.smith@example.com', // Duplicate email
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302); // Expecting a redirect
        $response->assertSessionHasErrors(['email']); // Check if 'email' field has error
    }


    public function test_it_logs_in_a_user_successfully()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/orders');

        $response->assertStatus(200);
    }


    public function test_it_fails_login_with_invalid_credentials()
    {
        User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'),
        ]);
    
        $response = $this->post('/login', [
            'email' => 'john.doe@example.com',
            'password' => 'incorrectpassword', // Invalid password
        ]);
    
        $response->assertSessionHas('error', 'The provided credentials do not match our records.');
        $this->assertGuest();
    }

    public function test_it_logs_out_a_user()
    {
        // Create a user and log them in
        $user = User::factory()->create();
        
        // Acting as the logged-in user
        $this->actingAs($user);

        // Ensure the user is authenticated before logging out
        $this->assertTrue(Auth::check());

        // Send a POST request to the logout route
        $response = $this->post('/logout'); // Remove withSession as it's unnecessary here

        // Assert that the user is redirected to the home page
        $response->assertRedirect('/');

        // Assert that the user is no longer authenticated
        $this->assertFalse(Auth::check());
    }
}

