<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'Example',
            'email' => 'admin@example.com',
            'password' => bcrypt('1q2w3e4r'), 
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            User::create([
                'firstname' => $faker->name,
                'lastname' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('1q2w3e4r'), 
                'role' => $faker->randomElement(['admin', 'user']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
