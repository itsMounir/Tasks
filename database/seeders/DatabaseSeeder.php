<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::insert([
            [
                'name' => 'owner',
            ],
            [
                'name' => 'super_admin'
            ],
            [
                'name' => 'admin'
            ],
            [
                'name' => 'supervisor'
            ]
        ]);

        Permission::insert([
            [
                'name' => 'create_user'
            ],
            [
                'name' => 'read_user'
            ],
            [
                'name' => 'update_user'
            ],
            [
                'name' => 'delete_user'
            ],
            [
                'name' => 'create_product'
            ],
            [
                'name' => 'read_product'
            ],
            [
                'name' => 'update_product'
            ],
            [
                'name' => 'delete_product'
            ],
            [
                'name' => 'create_category'
            ],
            [
                'name' => 'read_category'
            ],
            [
                'name' => 'update_category'
            ],
            [
                'name' => 'delete_category'
            ],
            [
                'name' => 'update_role'
            ]

        ]);


        User::factory()->create([
            'name' => 'Mounir',
            'email' => 'mounirtoo.22@gmail.com',
            'password' => bcrypt('123456879'),
            'role_id' => 1,
        ]);

        User::factory()->create([
            'name' => 'Goerge',
            'email' => 'george@example.com',
            'password' => bcrypt('123456879'),
            'role_id' => 2,
        ]);

        Category::factory()->create([
            'name' => 'Tech',
            'parent_id' => null,
        ]);

        Category::factory()->create([
            'name' => 'Candies',
            'parent_id' => null,
        ]);

        Product::factory()->create([
            'name' => 'Apple',
            'price' => 999,
            'category_id' => 1,
        ]);

        Product::factory()->create([
            'name' => 'Samsung',
            'price' => 99,
            'category_id' => 1,
        ]);



        User::factory(5)->create();
        Product::factory(5)->create();
        $this->attachPermissionsToRoles();

    }

    public function attachPermissionsToRoles()
    {
        $ownerRole = Role::where('name', 'owner')->first();
        $permissions = [1, 2, 3, 4, 5,6,7,8,9,10,11,12,13,];
        foreach ($permissions as $permission) {
            $ownerRole->permissions()->create(['permission_id' => $permission]);
        }

        $superAdminRole = Role::where('name', 'super_admin')->first();
        $permissions = [1, 4, 5, 8, 9, 12];
        foreach ($permissions as $permission) {
            $superAdminRole->permissions()->create(['permission_id' => $permission]);
        }

        $adminRole = Role::where('name', 'admin')->first();
        $permissions = [1, 5, 9];
        foreach ($permissions as $permission) {
            $adminRole->permissions()->create(['permission_id' => $permission]);
        }

        $supervisorRole = Role::where('name', 'supervisor')->first();
        $permissions = [2, 6, 10];
        foreach ($permissions as $permission) {
            $supervisorRole->permissions()->create(['permission_id' => $permission]);
        }
    }
}
