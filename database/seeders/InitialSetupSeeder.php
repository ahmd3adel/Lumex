<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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


        Role::create(['name' => 'admin']);
        Role::create(['name' => 'agent']);
        Role::create(['name' => 'employee']);
//
//        // إنشاء الصلاحيات
        Permission::create(['name' => 'see transfers']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'delete users']);

        $user1 = User::create([
            'name' => 'super admin',
            'email' => 'superadmin@gmail.com',
            'username' => 'superadmin',
            'phone' => '01016070900',
            'status'=> 'active',
            'password' => Hash::make('123456789')
        ]);
        $user2 = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'phone' => '01016070901',
            'password' => Hash::make('123456789')
        ]);
        $user2->assignRole('admin');

        $user3 = User::create([
            'name' => 'employee 1',
            'email' => 'employe1e@gmail.com',
            'username' => 'employee1',
            'phone' => '01016070902',
            'password' => Hash::make('123456789')
        ]);
        $user3->assignRole('employee');

        $user4 = User::create([
            'name' => 'employee2',
            'email' => 'employee2@gmail.com',
            'username' => 'employee2',
            'phone' => '01016070903',
            'password' => Hash::make('123456789')
        ]);
        $user4->assignRole('employee');

        $user5 = User::create([
            'name' => 'agent',
            'email' => 'agent1@gmail.com',
            'username' => 'agent1',
            'phone' => '01016070904',
            'password' => Hash::make('123456789')
        ]);
        $user5->assignRole('agent');

        $user6 = User::create([
            'name' => 'agent2',
            'email' => 'agent2@gmail.com',
            'username' => 'agent2',
            'phone' => '01016070905',
            'password' => Hash::make('123456789')
        ]);
        $user6->assignRole('admin');

        $user7 = User::create([
            'name' => 'agent3',
            'email' => 'agent3@gmail.com',
            'username' => 'agent3',
            'phone' => '01016070906',
            'password' => Hash::make('123456789')
        ]);
        $user7->assignRole('agent');


$user1->syncPermissions(Permission::all());


    }



}
