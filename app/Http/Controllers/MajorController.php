<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Major;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class MajorController extends Controller
{
    public function getAll()
    {
        $major = DB::table('majors as m')
        ->leftJoin('faculties as f','f.fac_id','=','m.fac_id')
        ->select('m.fac_id','m.name as name','m.m_id as m_id','f.name as fac_name')
        ->get();
        return datatables()->of($major)
            ->make(true);
    }

    public function getFac($id)
    {
        $fac = Major::where('m_id','=',$id)->value('fac_id');
        return $fac;
    }

    public function getMajor($id)
    {
        $major = Major::where('fac_id','=',$id)->get();
        return datatables()->of($major)->make(true);
    }

    public function getAllWhere($id)
    {
        $major = Major::where('fac_id','=',$id)->get();
        return datatables()->of($major)
            ->make(true);
    }

    public function test()
    {
        $msg = array(
            'error'  => 'ข้อมูลซ้ำ',
        );
        return response()->json($msg, 400);
    }

    public function update(Request $request)
    {
        $fac_id = $request->fac_id;
        $m_id = $request->m_id;
        $name = $request->name;

        $validator = Validator::make($request->all(), [
            'name' => Rule::unique('majors')->where(function ($query) use ($fac_id, $name, $m_id) {
                return $query->where('name', $name)
                             ->where('fac_id', $fac_id)
                             ->where('m_id','!=', $m_id);
            }),
        ]);

        if ($validator->fails()) {
            return response()->json(array('error'  => 'ข้อมูลซ้ำ',), 400);
        }

        Major::where('m_id', '=', $request->m_id)
            ->update([
                'name' => $request->name,
                'fac_id' => $request->fac_id
            ]);

        return response()->json(array('status'  => $request->name));
    }

    public function insert(Request $request)
    {
        $fac_id = $request->fac_id;
        $name = $request->name;

        $validator = Validator::make($request->all(), [
            'name' => Rule::unique('majors')->where(function ($query) use ($fac_id, $name) {
                return $query->where('name', $name)->where('fac_id', $fac_id);
            }),
        ]);

        if ($validator->fails()) {
            return response()->json(array('error'  => 'ข้อมูลซ้ำ',), 400);
        }

        $major = new Major([
            'name' => $request->name,
            'fac_id' => $request->fac_id,
        ]);
        $major->save();
        return response()->json(array('name' => $request->name));
    }

    public function delete($id)
    {
        Major::where('m_id', '=', $id)->delete();

        return response()->json(array('status'  => 'delete success'));
    }
}
