<?php

namespace App\Http\Controllers;

use App\Check;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckController extends Controller
{

    public function getdata($date, $s_id, $year, $term)
    {
        // นศ เข้าเรียน มีสถานะ
        $data = DB::table('checks as c')
            ->leftJoin('course_registrations as cr', 'c.cr_id', 'cr.cr_id')
            ->leftJoin('students as s', 'cr.std_id', 's.std_id')
            ->leftJoin('majors as m', 's.m_id', 'm.m_id')
            ->where('date', $date)
            ->where('year', $year)
            ->where('s_id', $s_id);


        $data_array = array();

        foreach ($data->get('cr.std_id')->toArray() as $d) {
            array_push($data_array, $d->std_id);
        }

        // นศทั้งหมด ที่ไม่มีสถานะ
        $all = DB::table('course_registrations as cr')
            ->leftJoin('students as s', 'cr.std_id', 's.std_id')
            ->leftJoin('majors as m', 's.m_id', 'm.m_id')
            ->where('s_id', $s_id)
            ->where('year', $year)
            ->where('term', $term)
            ->whereNotIn('cr.std_id', $data_array);
        //->get('cr.std_id');   

        // รวม Array

        $arr1 = $data->get([
            'c.cr_id',
            'c.chk_id',
            'c.status',
            'c.date',
            'c.detail',
            'cr.std_id',
            's.f_name',
            's.l_name',
            'm.name',
        ])->toArray();

        $arr2 = $all->get([
            'cr.cr_id',
            'cr.std_id',
            's.f_name',
            's.l_name',
            'm.name',
        ])->toArray();

        $merge = array_merge($arr1, $arr2);

        // return response()->json(array($merge));
        return datatables()->of($merge)->make(true);
    }

    public function insert(Request $request)
    {
        foreach ($request->data as $val) {
            if ($val["chk_id"] != null) {
                Check::where('chk_id', $val["chk_id"])
                    ->where('date', $val["date"])
                    ->where('cr_id', $val["cr_id"])
                    ->update([
                        'status' => $val["status"],
                    ]);
            }
            if ($val["chk_id"] == null && $val["date"] != null && $val["status"] != null) {
                $chk = new Check([
                    'cr_id' => $val["cr_id"],
                    'status' => $val["status"],
                    'date' => $val["date"],
                ]);
                $chk->save();
            }
        }
        return response()->json(array('message' => 'success'));
    }

    public function carlendar(Request $request)
    {
        $count = DB::select(
            "SELECT 
            COUNT(c.chk_id) as count,
            c.status ,
            c.date
            FROM checks as c
            LEFT JOIN course_registrations as cr ON c.cr_id = cr.cr_id
            RIGHT JOIN subjects as s ON cr.s_id = s.s_id
            AND cr.year = '$request->year'
            AND cr.s_id = '$request->s_id'
            AND cr.term = '$request->term'
            WHERE c.chk_id IS NOT NULL
            GROUP BY c.date,c.status
        "
        );


        $carlendar_data = array();

        // Loop Create Date Event

        foreach ($count as $val) {

            $date = $this->news_date_format($val->date);

            if ($val->status == '0') {
                $c = [
                    'id' => 1,
                    'title' => 'เข้าเรียน ' . $val->count,
                    'start' =>  $date,
                    'color' => 'green'
                ];
            }

            if ($val->status == '1') {
                $c = [
                    'id' => 2,
                    'title' => 'ขาดเรียน ' . $val->count,
                    'start' => $date,
                    'color' => 'red'
                ];
            }

            if ($val->status == '2') {
                $c = [
                    'id' => 3,
                    'title' => 'ลา ' . $val->count,
                    'start' => $date,
                    'color' => 'orange'
                ];
            }

            if (isset($c)) {
                array_push($carlendar_data, $c);
            }
        }

        return response()->json($carlendar_data);
        //return response()->json($request);
    }

    public function updateDetail(Request $request)
    {
        if ($request->chk_id != null) {
            Check::where('chk_id', $request->chk_id)
                ->update([
                    'detail' => $request->detail,
                ]);
        }
        if ($request->chk_id == null) {
            $check = new Check([]);
            $check->save();
        }
        return response()->json(array('msg' => $request->chk_id));
    }

    private function news_date_format($date)
    {
        $d_list = explode('-', $date);

        $d = $d_list[0];
        $m = $d_list[1];
        $y = intval($d_list[2]) - 543;

        return $y . '-' . $m . '-' . $d;
    }

    public function getInfo($cr_id) // For student // Calendar
    {
        $data = DB::select("
        SELECT c.date,c.status FROM checks as c 
        LEFT JOIN course_registrations as cr ON c.cr_id = cr.cr_id
        WHERE cr.cr_id = '$cr_id'
        ");

        $carlendar_data = array();

        foreach ($data as $val) {
            $new_date = $this->news_date_format($val->date);
            $stat = $val->status;
            $c = [
                'title' => $stat == '0' ? 'เข้าเรียน' : ($stat == '1' ? 'ขาด' : 'ลา'),
                'start' => $new_date,
                'color' => $stat == '0' ? 'green' : ($stat == '1' ? 'red' : 'orange'),
            ];
            array_push($carlendar_data,$c);
        }
        return response()->json($carlendar_data);
    }
}
