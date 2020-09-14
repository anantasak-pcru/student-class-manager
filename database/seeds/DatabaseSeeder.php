<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                PositionSeeder::class,
                FacultySeeder::class,
                MajorSeeder::class,
                AdminSeeder::class,
                StudentSeeder::class,
                TeacherSeeder::class,
                CheckSeeder::class,
                SubjectSeeder::class,
            ]
            );
    }
}
