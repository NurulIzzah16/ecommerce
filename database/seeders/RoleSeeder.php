<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
                'permissions' => json_encode([
                    'categories',
                    'categories.create',
                    'categories.edit',
                    'categories.delete',
                    'products',
                    'products.create',
                    'products.edit',
                    'products.delete',
                    'users',
                    'admins.create',
                    'admins.edit',
                    'admins.delete',
                    'orders',
                    'roles',
                    'roles.create',
                    'roles.edit',
                    'roles.delete',
                ]),
            ],
            [
                'name' => 'karyawan',
                'created_at' => now(),
                'updated_at' => now(),
                'permissions' => json_encode([
                    'categories',
                    'categories.create',
                    'categories.edit',
                    'categories.delete',
                    'products',
                    'products.create',
                    'products.edit',
                    'products.delete',
                ]),
            ],
        ]);
    }
}
