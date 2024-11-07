<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Seiko',
                'price' => 19.99,
                'image' => 'products/seiko.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Casio',
                'price' => 29.21,
                'image' => 'products/casio.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shock',
                'price' => 33.57,
                'image' => 'products/shock.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Audemars',
                'price' => 39.81,
                'image' => 'products/audemars.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rolex',
                'price' => 33.71,
                'image' => 'products/rolex.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Michaels',
                'price' => 82.91,
                'image' => 'products/michaels.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Citizen',
                'price' => 77.34,
                'image' => 'products/citizen.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Promaster',
                'price' => 81.88,
                'image' => 'products/promaster.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kors',
                'price' => 57.24,
                'image' => 'products/kors.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amazfit',
                'price' => 31.79,
                'image' => 'products/amazfit.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

