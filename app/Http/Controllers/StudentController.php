<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function getAll()
    {
        $student = DB::table('students as s')
            ->leftJoin('majors as m', 's.m_id', 'm.m_id')
            ->leftJoin('faculties as f', 'm.fac_id', 'f.fac_id')
            ->get([
                'std_id',
                'f_name',
                'l_name',
                'address',
                'tel',
                'f.fac_id',
                'm.m_id',
                'm.name as major',
                'f.name as faculty',
            ]);
        return datatables()->of($student)->make(true);
    }

    public function get($major, $fac, $yn)
    {
        $student = DB::table('students as s')
            ->leftJoin('majors as m', 's.m_id', 'm.m_id')
            ->leftJoin('faculties as f', 'm.fac_id', 'f.fac_id');
        if ($fac != 'null') {
            $student->where('f.fac_id', $fac);
        }

        if ($major != 'null') {
            $student->where('m.m_id', $major);
        }

        if ($yn != 'null') {
            $student->where('s.std_id', 'LIKE', $yn . '%');
        }
        $data = $student->get([
            'std_id',
            'f_name',
            'l_name',
            'address',
            'tel',
            'm.name as major',
            'f.name as faculty',
        ]);
        return datatables()->of($data)->make(true);
    }
    public function insert(Request $request)
    {
        $fname = $request->f_name;
        $lname = $request->l_name;

        $validator = Validator::make($request->all(), [
            'std_id' => 'required|min:12|unique:students',
            'f_name' => Rule::unique('students')->where(function ($query) use ($fname, $lname) {
                return $query->where('f_name', $fname)
                    ->where('l_name', $lname);
            }),
            'password' => 'required|min:6',
            'address' => 'required|max:100',
            'tel' => 'required|max:15',
            'm_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('status'  => 'Error',), 400);
        }

        $student = new Student([
            'std_id' => $request->std_id,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'address' => $request->address,
            'tel' => $request->tel,
            'password' => Hash::make($request->password, ['round' => 12]),
            'm_id' => $request->m_id,
        ]);
        $student->save();
        return response()->json(array('name' => $request->f_name), 200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $msg = array(
                'error'  => 'รหัสผ่านสั้นเกินไป',
            );
            return response()->json($msg, 400);
        }
        Student::where('std_id', '=', $request->std_id)
            ->update([
                'password' => Hash::make($request->password, ['round' => 12]),
            ]);
        return response()->json(array('status' => 'update success', 'id' => $request->std_id));
    }

    public function update(Request $request)
    {
        $fname = $request->f_name;
        $lname = $request->l_name;
        $std_id = $request->std_id;

        $validator = Validator::make($request->all(), [
            'f_name' => Rule::unique('students')->where(function ($query) use ($fname, $lname, $std_id) {
                return $query->where('f_name', $fname)
                    ->where('l_name', $lname)
                    ->where('std_id', '!=', $std_id);
            }),
            'address' => 'required|max:100',
            'tel' => 'required|max:15',
            'm_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $request->setting ? redirect()->to('/student/setting')->with('error','แก้ไขข้อมูลไม่สำเร็จ')
            : response()->json(array('status'  => 'Error',), 400);
        }

        Student::where('std_id', '=', $request->std_id)
            ->update([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'address' => $request->address,
                'tel' => $request->tel,
                'm_id' => $request->m_id,
            ]);
        return $request->setting ? redirect()->to('/student/setting')->with('success','แก้ไขข้อมูลเรียบร้อย')
        : response()->json(array('status' => 'update success', 'id' => $request->std_id));
    }

    public function delete($id)
    {
        $student = Student::where('std_id', '=', $id)->delete();
        if (!$student) {
            return response()->json(array('status' => 'error'), 400);
        }
        return response()->json(array('status' => 'delete success'));
    }

    public function index()
    {
        return view('student.index');
    }

    public function setting()
    {
        $id = session('session_id');
        $data = DB::table('students as s')
        ->leftJoin('majors as m','s.m_id','m.m_id')
        ->leftJoin('faculties as f','m.fac_id','f.fac_id')
        ->where('s.std_id',$id)
        ->get([
            'f_name',
            'l_name',
            'tel',
            'address',
            's.std_id',
            's.m_id',
            'm.name as major',
            'f.name as faculty'
        ])
        ->first();
        return view('student.setting',compact('data'));
    }
}
