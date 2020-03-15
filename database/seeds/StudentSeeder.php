<?php

use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            'std_id' => 'Student',
            'f_name' => 'Student',
            'l_name' => 'Student',
            'password' => Hash::make('password'),
            'address' => null,
            'password' => null,
            'm_id' => null,
        ]);
    }
}
