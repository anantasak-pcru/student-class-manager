<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function index()
    {
        return view('admins.student');
    }
    
    public function student()
    {
        return view('admins.student');
    }

    public function position()
    {
        return view('admins.position');
    }

    public function faculty()
    {
        return view('admins.faculty');
    }

    public function major()
    {
        return view('admins.major');
    }

    public function teacher()
    {
        return view('admins.teacher');
    }

    public function subject()
    {
        return view('admins.subject');
    }

    public function admin()
    {
        return view('admins.admin');
    }

    public function course_regist()
    {
        return view('admins.course_regist');
    }

    public function setting()
    {
        $id = session('session_id');
        $data = DB::table('admins')
        ->leftJoin('positions','admins.p_id','positions.p_id')
        ->where('admin_id',$id)
        ->first();
        // dd($id);
        return view('admins.setting',compact('data'));
    }

    public function getAll()
    {
        $admin = DB::table('admins as a')
        ->leftJoin('positions as p','p.p_id','=','a.p_id')
        ->select('admin_id','f_name','l_name','a.p_id','name')
        ->get();
        return datatables()->of($admin)->make(true);
    }

    public function insert(Request $request)
    {

        $fname = $request->f_name;
        $lname = $request->l_name;

        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|unique:admins',
            'f_name' => Rule::unique('admins')->where(function ($query) use ($fname, $lname) {
                return $query->where('f_name', $fname)
                    ->where('l_name', $lname);
            }),
            'password' => 'required|min:6',
            'p_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('status'  => $validator->errors(),), 400);
        }

       $admin = new Admin([
           'admin_id' => $request->admin_id,
           'f_name' => $request->f_name,
           'l_name' => $request->l_name,
           'password' => Hash::make($request->password,['round',12]),
           'p_id' => $request->p_id,
       ]);
       $admin->save();
       return response()->json(array('status' => 'create success','name' => $request->name));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(array('status'  => 'shortpassword'),400);
        }
        
       Admin::where('admin_id','=',$request->admin_id)
       ->update([
           'password' => Hash::make($request->password,['round',12]),
       ]);
       return response()->json(array('status' => 'update password success'));
    }

    public function update(Request $request)
    {
        $fname = $request->f_name;
        $lname = $request->l_name;
        $admin_id = $request->admin_id;

        $validator = Validator::make($request->all(), [
            'admin_id' => 'required',
            'f_name' => Rule::unique('admins')->where(function ($query) use ($fname, $lname, $admin_id) {
                return $query->where('f_name', $fname)
                    ->where('l_name', $lname)
                    ->where('admin_id', '!=', $admin_id);
            }),
            'p_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $request->setting ? redirect()->to('/admin/setting')->with('error','แก้ไขข้อมูลไม่สำเร็จ')
            : response()->json(array('status'  => 'Error',),400);
        }
        Admin::where('admin_id','=',$request->admin_id)
        ->update([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'p_id' => $request->p_id,
        ]);
        return $request->setting ? redirect()->to('/admin/setting')->with('success','แก้ไขข้อมูลเรียบร้อย') 
        : response()->json(array('status' => 'update success','id' => $request->admin_id));
    }

    public function delete($id)
    {
        Admin::where('admin_id','=',$id)->delete();
        return response()->json(array('status' => 'delete success'));
    }

    // Report

    public function studentReport()
    {
        return view('admins.student_report');
    }

    public function adminReport()
    {
        return view('admins.admin_report');
    }

    public function teacherReport()
    {
        return view('admins.teacher_report');
    }

    public function facultyReport()
    {
        return view('admins.faculty_report');
    }

    public function majorReport()
    {
        return view('admins.major_report');
    }

    public function positionReport()
    {
        return view('admins.position_report');
    }

    public function subjectReport()
    {
        return view('admins.subject_report');
    }

    public function course_registReport()
    {
        return view('admins.course_regist_report');
    }

}
