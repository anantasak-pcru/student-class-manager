<?php

namespace App\Http\Controllers;

use App\Subject;
use App\Teacher;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(array('status'  => 'shortpassword'), 400);
        }

        Teacher::where('t_id', '=', $request->t_id)
            ->update([
                'password' => Hash::make($request->password, ['round' => 12]),
            ]);
        return response()->json(array('status' => 'update success', 'id' => $request->t_id));
    }

    public function delete($id)
    {
        $student = Teacher::where('t_id', '=', $id)->delete();
        if (!$student) {
            return response()->json(array('status' => 'error'), 400);
        }
        return response()->json(array('status' => 'delete success'));
    }

    public function getAll()
    {
        $teacher = DB::table('teachers as t')
            ->leftJoin('positions as p', 't.p_id', 'p.p_id')
            ->leftJoin('majors as m', 't.m_id', 'm.m_id')
            ->leftJoin('faculties as f', 'm.fac_id', 'f.fac_id')
            ->get([
                't_id',
                'f_name',
                'l_name',
                'tel',
                'address',
                'm.name as major',
                'f.name as faculty',
                'p.name as position',
                't.p_id',
                't.m_id',
            ]);
        return datatables()->of($teacher)->make(true);
    }

    public function get($id)
    {
        $teacher = Teacher::where('m_id', '=', $id)->get(['t_id', 'f_name', 'l_name']);
        return datatables()->of($teacher)->make(true);
    }

    public function getReport($m_id, $fac_id)
    {
        $teacher = DB::table('teachers as t')
            ->leftJoin('positions as p', 't.p_id', 'p.p_id')
            ->leftJoin('majors as m', 't.m_id', 'm.m_id')
            ->leftJoin('faculties as f', 'm.fac_id', 'f.fac_id');
        if ($m_id != 'null') {
            $teacher->where('t.m_id', $m_id);
        }
        if ($fac_id != 'null') {
            $teacher->where('m.fac_id', $fac_id);
        }
        $data = $teacher->get([
            't_id',
            'f_name',
            'l_name',
            'tel',
            'address',
            'm.name as major',
            'f.name as faculty',
            'p.name as position',
            't.p_id',
            't.m_id',
        ]);
        return datatables()->of($data)->make(true);
    }

    public function getMajor($id)
    {
        $m_id = Teacher::where('t_id', '=', $id)->value('m_id');
        return $m_id;
    }

    public function insert(Request $request)
    {
        $fname = $request->f_name;
        $lname = $request->l_name;

        $validator = Validator::make($request->all(), [
            't_id' => 'required|unique:teachers',
            'f_name' => Rule::unique('teachers')->where(function ($query) use ($fname, $lname) {
                return $query->where('f_name', $fname)
                    ->where('l_name', $lname);
            }),
            'password' => 'required|min:6',
            'address' => 'required|max:100',
            'tel' => 'required|max:15',
            'm_id' => 'required',
            'p_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('status'  => 'Error',), 400);
        }

        $student = new Teacher([
            't_id' => $request->t_id,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'address' => $request->address,
            'tel' => $request->tel,
            'password' => Hash::make($request->password, ['round' => 12]),
            'm_id' => $request->m_id,
            'p_id' => $request->p_id,
        ]);
        $student->save();
        return response()->json(array('name' => $request->f_name), 200);
    }

    public function update(Request $request)
    {
        $fname = $request->f_name;
        $lname = $request->l_name;
        $t_id = $request->t_id;

        $validator = Validator::make($request->all(), [
            't_id' => 'required',
            'f_name' => Rule::unique('teachers')->where(function ($query) use ($fname, $lname, $t_id) {
                return $query->where('f_name', $fname)
                    ->where('l_name', $lname)
                    ->where('t_id', '!=', $t_id);
            }),
            'address' => 'required|max:100',
            'tel' => 'required|max:15',
            'm_id' => 'required',
            'p_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $request->setting ? redirect()->to('/teacher/setting')->with('error', 'แก้ไขข้อมูลไม่สำเร็จ')
                : response()->json(array('status'  => 'Error'), 400);
        }

        Teacher::where('t_id', '=', $request->t_id)
            ->update([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'address' => $request->address,
                'tel' => $request->tel,
                'm_id' => $request->m_id,
                'p_id' => $request->p_id,
            ]);

        return $request->setting ? redirect()->to('/teacher/setting')->with('success', 'แก้ไขข้อมูลเรียบร้อย')
            : response()->json(array('status' => 'update success', 'id' => $request->t_id));
    }

    public function index()
    {
        return view('teacher.check');
    }

    public function report()
    {
        return view('teacher.check_report');
    }

    public function report2($s_id,$year,$term)
    {
        $s_name = Subject::where('s_id',$s_id)
        ->first();

        $val = array();

        $data = DB::table('checks as c')
            ->leftJoin('course_registrations as cr', 'c.cr_id', 'c.cr_id')
            ->where('cr.year', $year)
            ->where('cr.s_id', $s_id)
            ->where('cr.term', $term)
            ->whereNotNull('c.status')
            ->distinct()
            ->get('c.date')->toArray();
        
            $data2 = $data;

        foreach ($data as $date) {


            // นศ เข้าเรียน มีสถานะ
            $data = DB::table('checks as c')
                ->leftJoin('course_registrations as cr', 'c.cr_id', 'cr.cr_id')
                ->leftJoin('students as s', 'cr.std_id', 's.std_id')
                ->leftJoin('majors as m', 's.m_id', 'm.m_id')
                ->where('date', $date->date)
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

            array_push($val,$merge);
        }

        $value = $val;
        $value2 = $data2;
        // END require

        return view('teacher.check_report2',compact('value','value2','s_name'));
        return response()->json($value);
        // return response()->json($data);
        // return datatables()->of($data)->make(true);
    }

    public function studentreport()
    {
        return view('teacher.student_report');
    }
    public function report3()
    {
        $id = session('session_id');
        $subject = DB::table('subjects')
        ->where('t_id',$id)
        ->get();
        return view('teacher.check_report3',compact('subject'));
    }

    public function setting()
    {
        $id = session('session_id');
        $data = DB::table('teachers as t')
            ->leftJoin('positions as p', 't.p_id', 'p.p_id')
            ->leftJoin('majors as m', 't.m_id', 'm.m_id')
            ->leftJoin('faculties as f', 'm.fac_id', 'f.fac_id')
            ->where('t.t_id', $id)
            ->get([
                'f_name',
                'l_name',
                'tel',
                'address',
                't.t_id',
                't.m_id',
                'p.p_id',
                'm.name as major',
                'f.name as faculty'
            ])
            ->first();
        //dd($data);
        return view('teacher.setting', compact('data'));
    }
}
