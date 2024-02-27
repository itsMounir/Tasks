<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Mounir',
            'email' => 'mounir@example.com',
        ]);

        User::factory()->create([
            'name' => 'Goerge',
            'email' => 'george@example.com',
        ]);

        Category::factory()->create([
            'name' => 'Tech',
            'parent_id' => null,
            'type' => 'main'
        ]);

        Category::factory()->create([
            'name' => 'Candies',
            'parent_id' => null,
            'type' => 'main'
        ]);

        Product::factory()->create([
            'name' => 'Apple',
            'price' => 999 ,
            'category_id' => 1,
        ]);

        Product::factory()->create([
            'name' => 'Samsung',
            'price' => 99 ,
            'category_id' => 1,
        ]);

        User::factory(500)->create();
        Product::factory(500)->create();


    }
}
