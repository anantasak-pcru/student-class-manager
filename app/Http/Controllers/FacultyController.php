<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faculty;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    public function getAll()
    {
        $faculty = Faculty::orderBy('name', 'asc')->get();
        return datatables()->of($faculty)
            ->make(true);
    }

    public function update(Request $request)
    {
        $name = $request->name;
        $fac_id = $request->fac_id;

        $validator = Validator::make($request->all(), [
            'name' => Rule::unique('faculties')->where(function ($query) use ($name, $fac_id) {
                return $query->where('name', $name)
                    ->where('fac_id', '!=', $fac_id);
            }),
        ]);

        if ($validator->fails()) {
            return response()->json(array('error'  => 'ข้อมูลซ้ำ',), 400);
        }

        Faculty::where('fac_id', '=', $request->fac_id)
            ->update([
                'name' => $request->name,
            ]);
        $msg = array(
            'status'  => $request->name,
        );
        return response()->json($msg);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:faculties',
        ]);

        if ($validator->fails()) {
            return response()->json(array('error'  => 'ข้อมูลซ้ำ'), 400);
        }

        $faculty = new Faculty([
            'name' => $request->name,
        ]);

        $faculty->save();

        $msg = array(
            'status'  => 'create success',
            'name' => $request->name,
        );
        return response()->json($msg);
    }

    public function delete($id)
    {
        Faculty::where('fac_id', '=', $id)->delete();
        $msg = array(
            'status'  => 'delete success',
        );
        return response()->json($msg);
    }
}
