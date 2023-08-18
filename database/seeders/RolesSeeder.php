<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $permissions = [
            'create-users', 'read-users', 'update-users', 'delete-users',
            'create-merchants', 'read-merchants', 'update-merchants', 'delete-merchants',
            'create-orders', 'read-orders', 'update-orders', 'delete-orders',
            'create-products', 'read-products', 'update-products', 'delete-products'
        ];

        foreach ($permissions as $key => $permission) {
            $findPermission = Permission::where('name', '=', $permission)->get()->first();
            if ($findPermission == null) {
                Permission::create(['name' => $permission]);
            }
        }

        $roles = ['admin', 'manager'];

        $permissionsToExclude = [12,14];
        $permissionsManager = array_filter($permissions, function ($key) use ($permissionsToExclude) {
            return !in_array($key, $permissionsToExclude);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($roles as $key => $role) {
            $findRole = Role::where('name', '=', $role)->get()->first();
            if ($findRole == null) {
                $findRole = Role::create(['name' => $role]);
            }
            switch ($findRole->name) {
                case 'admin':
                    $findRole->syncPermissions($permissions);
                    break;
                case 'manager':
                    $findRole->syncPermissions($permissionsManager);
                default:
                    break;
            }
            
        }

        $dataUser1 = [
            'full_name' => 'Mateus Gabriel',
            'is_admin' => true,
            'email' => 'admin123@example.com',
            'password' => Hash::make('ihold#1234'),
        ];
        $user1 = UserRepository::createUserIfNotExist($dataUser1);
        $user1->assignRole('admin');

        $dataUser2 = [
            'full_name' => 'Rinaldo Peligrineli',
            'is_admin' => false,
            'email' => 'manager123@example.com',
            'password' => Hash::make('ihold#1234'),
        ];
        $user2 = UserRepository::createUserIfNotExist($dataUser2);
        $user2->assignRole('manager');
    }
}
