<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Position;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{

    public function getAll(){
        $position = Position::all();
        return datatables()->of($position)
            ->make(true);
    }

    public function update(Request $request)
    {
        $p_id = $request->p_id;
        $name = $request->name;
        $validator = Validator::make($request->all(), [
            'name' => Rule::unique('positions')->where(function ($query) use ($p_id, $name) {
                return $query->where('name', $name)->where('p_id','!=', $p_id);
            }),
        ]);

        if ($validator->fails()) {
            return response()->json(array('error'  => 'ข้อมูลซ้ำ',), 400);
        }
        
        Position::where('p_id','=',$request->p_id)
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
            'name' => 'required|unique:positions',
        ]);

        if ($validator->fails()) {
            return response()->json(array('error'  => 'ข้อมูลซ้ำ'), 400);
        }

        $position = new Position([
            'name' => $request->name,
        ]);
        $position->save();
        $msg = array(
            'status'  => 'create success',
        );
        return response()->json($msg);
    }

    public function delete($id)
    {
        Position::where('p_id','=',$id)->delete();
        $msg = array(
            'status'  => 'delete success',
        );
        return response()->json($msg);
    }
}
