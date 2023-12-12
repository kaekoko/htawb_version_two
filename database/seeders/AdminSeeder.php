<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Null_;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('super_admins')->insert([
            'id' => '1',
            'super_admin_id' => Null,
            'name' => 'Super Admin',
            'phone' => '09123456',
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);
    }
}
