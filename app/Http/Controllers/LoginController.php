<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Student;
use App\Teacher;
use Session;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        switch($request->stat){
            case 'admin':
                $data = $this->admin($request->username,$request->password);
                return response()->json($data,$data['status']);
            case 'teacher':
                $data = $this->teacher($request->username,$request->password);
                return response()->json($data,$data['status']);
            case 'student':
                $data = $this->student($request->username,$request->password);
                return response()->json($data,$data['status']);
            default:
                return response()->json(array('status'  => 'error','message' => 'Login Fail !'),400);
        }
        
    }

    public function logout(){
        Session::flush();
        auth()->logout();
        return redirect('/');
    }

    private function admin($username,$password)
    {
        if (Auth::guard('admin')->attempt(['admin_id' => $username, 'password' => $password], false)) {
            
            $data = Admin::find($username);
            session()->put('session_id', $data->admin_id);
            session()->put('session_fname', $data->f_name);
            session()->put('session_lname', $data->l_name);
            
            $msg = array(
                'status'  => 200,
                'message' => 'Login Successful',
                'session' => $data->admin_id,
                'name' => $data->f_name,
            );
            return $msg;
        } else {
            $msg = array(
                'status'  => 400,
                'message' => 'Login Fail ! , User Not Found',
                'username' => $username,
                'password' => $password
            );
            return $msg;
        }

    }

    private function student($username,$password)
    {
        if (Auth::guard('student')->attempt(['std_id' => $username, 'password' => $password], false)) {
            
            $data = Student::find($username);
            session()->put('session_id', $data->std_id);
            session()->put('session_fname', $data->f_name);
            session()->put('session_lname', $data->l_name);
            
            $msg = array(
                'status'  => 200,
                'message' => 'Login Successful',
                'session' => $data->admin_id,
                'name' => $data->f_name,
            );

            return $msg;
        } else {
            $msg = array(
                'status'  => 400,
                'message' => 'Login Fail ! , User Not Found',
                'username' => $username,
                'password' => $password
            );
            return $msg;
        }
    }

    private function teacher($username,$password)
    {
        if (Auth::guard('teacher')->attempt(['t_id' => $username, 'password' => $password], false)) {
            
            $data = Teacher::find($username);
            session()->put('session_id', $data->t_id);
            session()->put('session_fname', $data->f_name);
            session()->put('session_lname', $data->l_name);
            
            $msg = array(
                'status'  => 200,
                'message' => 'Login Successful',
                'session' => $data->t_id,
                'name' => $data->f_name,
            );

            return $msg;
        } else {
            $msg = array(
                'status'  => 400,
                'message' => 'Login Fail !'
            );
            return $msg;
        }
    }
}
