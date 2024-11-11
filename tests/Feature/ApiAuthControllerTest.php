<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_a_user_successfully()
    {
        $response = $this->postJson(route('api.register'), [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user' => ['id', 'firstname', 'lastname', 'email'],
                     'token',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);
    }

    public function test_it_fails_registration_with_missing_fields()
    {
        $response = $this->postJson(route('api.register'), [
            'firstname' => 'John',
            // 'lastname' => 'Doe', // missing lastname
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['lastname']);
    }

    public function test_it_fails_registration_with_invalid_email_format()
    {
        $response = $this->postJson(route('api.register'), [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'invalid-email-format',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_it_fails_registration_with_duplicate_email()
    {
        User::factory()->create(['email' => 'john.doe@example.com']);

        $response = $this->postJson(route('api.register'), [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com', // duplicate email
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_it_logs_in_a_user_successfully()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('api.login'), [
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => ['id', 'firstname', 'lastname', 'email'],
                     'token',
                 ]);
    }

    public function test_it_fails_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson(route('api.login'), [
            'email' => 'john.doe@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Invalid credentials']);
    }

    public function test_it_fails_login_with_missing_fields()
    {
        $response = $this->postJson(route('api.login'), [
            // 'email' => 'john.doe@example.com', // missing email
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}

