<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use FacultySeeder as F;

class MajorSeeder extends Seeder
{
    private $faculty;

    public function __construct()
    {
        $this->faculty = new F();
    }
    public function run()
    {
        $this->human();
        $this->manage();
        $this->science();
        $this->edu();
        $this->agriculture();

    }

    private function human()
    {
        $id = $this->faculty->human_id;
        DB::table('majors')->insert([
            [
                'name' => 'การพัฒนาสังคม',
                'fac_id' => $id
            ],
            [
                'name' => 'ภาษาอังกฤษ',
                'fac_id' => $id
            ],
            [
                'name' => 'ภาษาอังกฤษธุรกิจบริการ',
                'fac_id' => $id
            ],
            [
                'name' => 'สารสนเทศศาสตร์',
                'fac_id' => $id
            ],
            [
                'name' => 'ดนตรีและการแสดง',
                'fac_id' => $id
            ],
            [
                'name' => 'ศิลปกรรม',
                'fac_id' => $id
            ],
            [
                'name' => 'รัฐประศาสนศาสตรบัณฑิต',
                'fac_id' => $id
            ],
            [
                'name' => 'รัฐประศาสนศาสตร์',
                'fac_id' => $id
            ],
            [
                'name' => 'การบริหารทรัพยากรมนุษย์',
                'fac_id' => $id
            ],
            [
                'name' => 'การบริหารการปกครองท้องถิ่น',
                'fac_id' => $id
            ],
            [
                'name' => 'ยุทธศาสตร์การพัฒนาตามหลักปรัชญาของเศรษฐกิจพอเพียง',
                'fac_id' => $id
            ],
            [
                'name' => 'นิติศาสตร์',
                'fac_id' => $id
            ],
            [
                'name' => 'รัฐศาสตร์(การเมืองการปกครอง)',
                'fac_id' => $id
            ],
            [
                'name' => 'รัฐศาสตร์(การเมืองท้องถิ่น)',
                'fac_id' => $id
            ],
        ]);
    }

    public function manage()
    {
        $id = $this->faculty->manage_science_id;
        DB::table('majors')->insert([
            [
                'name' => 'ประชาสัมพันธ์',
                'fac_id' => $id
            ],
            [
                'name' => 'บัญชี',
                'fac_id' => $id
            ],
            [
                'name' => 'การจัดการ',
                'fac_id' => $id
            ],
            [
                'name' => 'การตลาด',
                'fac_id' => $id
            ],
            [
                'name' => 'การจัดการทรัพยากรมนุษย์',
                'fac_id' => $id
            ],
            [
                'name' => 'การจัดการท่องเที่ยวและโรงแรม',
                'fac_id' => $id
            ],
            [
                'name' => 'คอมพิวเตอร์ธุรกิจ',
                'fac_id' => $id
            ]
        ]);
    }
    public function science()
    {
        $id = $this->faculty->science_technology_id;
        DB::table('majors')->insert([
            [
                'name' => 'เคมี',
                'fac_id' => $id
            ],
            [
                'name' => 'ฟิสิกส์',
                'fac_id' => $id
            ],
            [
                'name' => 'ชีววิทยา',
                'fac_id' => $id
            ],
            [
                'name' => 'คณิตศาสตร์',
                'fac_id' => $id
            ],
            [
                'name' => 'สาธารณสุขศาสตร์',
                'fac_id' => $id
            ],
            [
                'name' => 'เทคโนโลยีสารสนเทศ',
                'fac_id' => $id
            ],
            [
                'name' => 'วิทยาการคอมพิวเตอร์',
                'fac_id' => $id
            ],
            [
                'name' => 'วิทยาศาสตร์และสิ่งแวดล้อม',
                'fac_id' => $id
            ],
            [
                'name' => 'วิทยาศาสตร์และเทคโนโลยีการอาหาร',
                'fac_id' => $id
            ],
        ]);
    }
    public function edu()
    {
        $id = $this->faculty->edu_id;
        DB::table('majors')->insert([
            [
                'name' => 'การศึกษาปฐมวัย',
                'fac_id' => $id
            ],
            [
                'name' => 'พลศึกษา',
                'fac_id' => $id
            ],
            [
                'name' => 'คณิตศาสตร์',
                'fac_id' => $id
            ],
            [
                'name' => 'วิทยาศาสตร์ทั่วไป',
                'fac_id' => $id
            ],
            [
                'name' => 'ภาษาอังกฤษ',
                'fac_id' => $id
            ],
            [
                'name' => 'ภาษาไทย',
                'fac_id' => $id
            ],
            [
                'name' => 'การงานอาชีพและเทคโนโลยี',
                'fac_id' => $id
            ]
        ]);
    }
    public function agriculture()
    {
        $id = $this->faculty->agriculture_technology_id;
        DB::table('majors')->insert([
            [
                'name' => 'เทคโนโลยีอุตสาหกรรม (เทคโนโลยีการผลิต)',
                'fac_id' => $id
            ],
            [
                'name' => 'เทคโนโลยีอุตสาหกรรม (เทคโนโลยีไฟฟ้าอุตสาหกรรม)',
                'fac_id' => $id
            ],
            [
                'name' => 'เทคโนโลยีอุตสาหกรรม (เทคโนโลยีอุตสาหกรรมก่อสร้าง)',
                'fac_id' => $id
            ],
            [
                'name' => 'เทคโนโลยีอุตสาหกรรม (เทคโนโลยีคอมพิวเตอร์อุตสาหกรรม)',
                'fac_id' => $id
            ],
            [
                'name' => 'เทคโนโลยีอุตสาหกรรม (เทคโนโลยีอิเล็คทรอนิกส์)',
                'fac_id' => $id
            ],
            [
                'name' => 'ออกแบบผลิตภัณฑ์อุตสาหกรรม',
                'fac_id' => $id
            ],
            [
                'name' => 'เกษตรศาสตร์ (พืชศาสตร์)',
                'fac_id' => $id
            ],
            [
                'name' => 'เกษตรศาสตร์ (สัตวศาสตร์)',
                'fac_id' => $id
            ],
            [
                'name' => 'เกษตรศาสตร์ (การจัดการการเกษตร)',
                'fac_id' => $id
            ],
            [
                'name' => 'เกษตรศาสตร์ (คหกรรมศาสตร์)',
                'fac_id' => $id
            ],
            [
                'name' => 'วิศวกรรมคอมพิวเตอร์',
                'fac_id' => $id
            ],
            [
                'name' => 'วิศวกรรมการผลิต',
                'fac_id' => $id
            ],
        ]);
    }
}
