<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $permissions[0] = Permission::create(['name' => 'create-users']);
        $permissions[1] = Permission::create(['name' => 'read-users']);
        $permissions[2] = Permission::create(['name' => 'update-users']);
        $permissions[3] = Permission::create(['name' => 'delete-users']);

        $permissions[4] = Permission::create(['name' => 'create-merchants']);
        $permissions[5] = Permission::create(['name' => 'read-merchants']);
        $permissions[6] = Permission::create(['name' => 'update-merchants']);
        $permissions[7] = Permission::create(['name' => 'delete-merchants']);

        $permissions[8] = Permission::create(['name' => 'create-orders']);
        $permissions[9] = Permission::create(['name' => 'read-orders']);
        $permissions[10] = Permission::create(['name' => 'update-orders']);
        $permissions[11] = Permission::create(['name' => 'delete-orders']);

        $permissions[12] = Permission::create(['name' => 'create-products']);
        $permissions[13] = Permission::create(['name' => 'read-products']);
        $permissions[14] = Permission::create(['name' => 'update-products']);
        $permissions[15] = Permission::create(['name' => 'delete-products']);

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleManager = Role::create(['name' => 'manager']);
        $roleAdmin->syncPermissions($permissions);

        $permissionsToExclude = [4,5,6,7];
        $permissionsManager = array_filter($permissions, function ($key) use ($permissionsToExclude) {
            return !in_array($key, $permissionsToExclude);
        }, ARRAY_FILTER_USE_KEY);

        $roleManager->syncPermissions($permissionsManager);

        $dataUser1 = [
            'full_name' => 'Mateus Gabriel',
            'is_admin' => true,
            'email' => 'admin123@example.com',
            'password' => Hash::make('ihold#1234'),
        ];
        $user1 = UserService::createUserIfNotExist($dataUser1);
        $user1->assignRole($roleAdmin);

        $dataUser1 = [
            'full_name' => 'Rinaldo Peligrineli',
            'is_admin' => true,
            'email' => 'admin123@example.com',
            'password' => Hash::make('ihold#1234'),
        ];
        $user1 = UserService::createUserIfNotExist($dataUser1);
        $user1->assignRole($roleAdmin);
    }
}
