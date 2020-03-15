<?php

use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teachers')->insert([
            't_id' => 'Teacher',
            'f_name' => 'Teacher',
            'l_name' => 'Teacher',
            'password' => Hash::make('password'),
            'address' => null,
            'tel' => null,
            'm_id' => null,
            'p_id' => null,
        ]);
    }
}
