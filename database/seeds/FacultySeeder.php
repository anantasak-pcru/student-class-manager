<?php

use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{

    // Base on http://academic.pcru.ac.th/index/admission/home/program.html
    public $edu_id = '1';
    public $human_id = '2';
    public $manage_science_id = '3';
    public $science_technology_id = '4';
    public $agriculture_technology_id = '5';

    public function run()
    {
        DB::table('faculties')->insert(
            [
                ['name' => 'คณะครุศาสตร์'],
                ['name' => 'คณะมนุษยศาสตร์และสังคมศาสตร์'],
                ['name' => 'คณะวิทยาการจัดการ'],
                ['name' => 'คณะวิทยาศาสตร์และเทคโนโลยี'],
                ['name' => 'คณะเทคโนโลยีการเกษตรและเทคโนโลยีอุตสาหกรรม']
            ]
        );
    }
}
