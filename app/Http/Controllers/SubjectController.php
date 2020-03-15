<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function getAll()
    {
        $major = DB::table('subjects as s')
        ->leftJoin('teachers as t','t.t_id','=','s.t_id')
        ->select('s_id','name','f_name','l_name','s.t_id',DB::raw('CONCAT(f_name," ",l_name) as full_name'))
        ->get();
        return datatables()->of($major)
            ->make(true);
    }

    public function update(Request $request)
    {
        $s_id = $request->s_id;
        $name = $request->name;
        $t_id = $request->t_id;

        $validator = Validator::make($request->all(), [
            'name' => Rule::unique('subjects')->where(function ($query) use ($t_id, $name,$s_id) {
                return $query->where('name', $name)
                             ->where('t_id', $t_id)
                             ->where('s_id','!=',$s_id);
            }),
        ]);
        if ($validator->fails()) {
            return response()->json(array('error'  => 'ข้อมูลซ้ำ',), 400);
        }

        Subject::where('s_id',$s_id)
            ->update([
                'name' => $request->name,
                't_id' => $request->t_id
            ]);

        return response()->json(array('status'  => $request->name,'s_id'  => $request->s_id,'t_id'  => $request->t_id));
    }

    public function insert(Request $request)
    {
        $teacher = new Subject([
            's_id' => $request->s_id,
            't_id' => $request->t_id,
            'name' => $request->name,
        ]);
        $teacher->save();
        return response()->json(array('name' => $request->name),200);
    }

    public function delete($id)
    {
        Subject::where('s_id', '=', $id)->delete();
        return response()->json(array('status'  => 'delete success'));
    }

    public function get($t_id)
    {
        $data = DB::table('subjects')
        ->where('t_id',$t_id)
        ->get();
        return response()->json($data);
    }
}
