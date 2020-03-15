<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'admin_id' => 'Admin',
            'f_name' => 'Admin',
            'l_name' => 'Admin',
            'password' => Hash::make('password'),
            'p_id' => null
        ]);
    }
}
