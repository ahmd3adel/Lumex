<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitialSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الأدوار
        $this->createRoles();

        // إنشاء الصلاحيات وربطها بالأدوار
        $this->createPermissions();

        // إنشاء المتجر الرئيسي
        $mainStore = Store::firstOrCreate([
            'name' => 'النور للحلول البرمجية',
            'location' => 'cairo',
        ]);

        // إنشاء المستخدمين وربطهم بالأدوار والصلاحيات
        $this->createUsers($mainStore);
    }

    /**
     * إنشاء الأدوار.
     */
    private function createRoles()
    {
        $roles = ['super admin', 'admin', 'agent', 'employee'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    /**
     * إنشاء الصلاحيات وربطها بالأدوار.
     */
    private function createPermissions()
    {
        $permissions = [
            'see transfers',
            'create users',
            'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /**
     * إنشاء المستخدمين وربطهم بالأدوار.
     */
    private function createUsers($mainStore)
    {
        $usersData = [
            [
                'name' => 'super admin',
                'email' => 'superadmin@gmail.com',
                'username' => 'superadmin',
                'status' => 'active',
                'phone' => '01016070900',
                'store_id' => $mainStore->id,
                'role' => 'super admin',
                'permissions' => Permission::all(), // جميع الصلاحيات
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'phone' => '01016070901',
                'store_id' => Store::inRandomOrder()->first()->id,
                'role' => 'admin',
            ],
            [
                'name' => 'employee 1',
                'email' => 'employee1@gmail.com',
                'username' => 'employee1',
                'phone' => '01016070902',
                'store_id' => Store::inRandomOrder()->first()->id,
                'role' => 'employee',
            ],
            [
                'name' => 'employee 2',
                'email' => 'employee2@gmail.com',
                'username' => 'employee2',
                'phone' => '01016070903',
                'store_id' => Store::inRandomOrder()->first()->id,
                'role' => 'employee',
            ],
            [
                'name' => 'agent 1',
                'email' => 'agent1@gmail.com',
                'username' => 'agent1',
                'phone' => '01016070904',
                'store_id' => Store::inRandomOrder()->first()->id,
                'role' => 'agent',
            ],
            [
                'name' => 'agent 2',
                'email' => 'agent2@gmail.com',
                'username' => 'agent2',
                'phone' => '01016070905',
                'store_id' => Store::inRandomOrder()->first()->id,
                'role' => 'admin',
            ],
        ];

        foreach ($usersData as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'phone' => $userData['phone'],
                    'store_id' => $userData['store_id'],
                    'password' => Hash::make('123456789'),
                ]
            );

            // ربط المستخدم بالدور
            $user->assignRole($userData['role']);

            // إذا كان المستخدم لديه صلاحيات إضافية
            if (isset($userData['permissions'])) {
                $user->syncPermissions($userData['permissions']);
            }
        }
    }
}
