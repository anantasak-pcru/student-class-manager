<?php

namespace App\Http\Controllers;

use App\Course_registration as Course;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseRegistController extends Controller
{
    public function get($id)
    {
        $raw = DB::select(
            "SELECT std_id"
        );
        return datatables()->of($raw)->make(true);
    }

    public function getYear()
    {
        $year = Course::distinct('year')->get('year');
        return response()->json($year);
    }

    public function dummy()
    {
        return datatables()->of(array())->make(true);
    }

    public function getYn()
    {
        $student = DB::select("SELECT DISTINCT(SUBSTRING(R.std_id,1,2)) AS 'year' FROM students AS R");
        return response()->json($student);
    }

    public function getdummy()
    {
        $dummy = "SELECT s.std_id ,s.f_name, s.l_name FROM students s,course_registrations c WHERE c.std_id = s.std_id AND s.m_id = '7' AND s.std_id LIKE '60%' AND c.s_id = '11' AND c.year = '2019' AND c.term = '1' ";
        $data = DB::select($dummy);
        return response()->json($data);
    }

    public function insert(Request $request)
    {
        $data = $request->s_id;
        foreach ($request->val as $val) {
            $array[] = [
                'std_id' => '' . $val,
                'year' => $request->year,
                'term' => $request->term,
                's_id' => $request->s_id,
            ];
        }
        Course::insert($array);
        return response()->json(array('lengtt' => $data));
    }

    public function remove(Request $request)
    {
        foreach ($request->val as $val) {
            $array[] = [
                'cr_id' => '' . $val,
            ];
        }
        Course::whereIn('cr_id', $array)->delete();
        return response()->json(array('lengtt' => $request->val));
    }

    public function getInclass(/*$m_id,*/$s_id,  $year, $term)
    {
        if (/*!$m_id ||*/!$s_id /*|| !$yn*/ || !$year || !$term) {
            return datatables()->of(array())->make(true);
        }
        $sql = "SELECT c.cr_id,s.std_id ,s.f_name, s.l_name,m.name as major_name,f.name as fac_name
        FROM students as s,course_registrations as c,majors as m , faculties as f
        WHERE c.std_id = s.std_id  
        AND c.s_id = '$s_id' 
        AND c.year = '$year' AND c.term = '$term' 
        AND m.m_id = s.m_id AND m.fac_id = f.fac_id";
        $data = DB::select($sql);
        return datatables()->of($data)->make(true);
        //s.m_id = '$m_id'
        //AND s.std_id LIKE '$yn%'
    }

    public function getNotInclass($yn, $fac_id, $m_id, $s_id, $year, $term)
    {
        if (!$s_id || !$year || !$term) {
            return datatables()->of(array())->make(true);
        }

        $inclass = Course::where('s_id', '=', $s_id)
            ->where('year', '=', $year)
            ->where('term', '=', $term)
            ->get('std_id')->toArray();

        $notinclass = DB::table('students as s')
            ->leftJoin('majors as m', 'm.m_id', '=', 's.m_id')
            ->leftJoin('faculties as f', 'f.fac_id', '=', 'm.fac_id')
            ->whereNotIn('std_id', $inclass);

        if ($yn != "null") {
            $notinclass->where('s.std_id', 'LIKE', $yn.'%');
        }

        if ($fac_id != "null") {
            $notinclass->where('f.fac_id', '=', $fac_id);
        }

        if ($m_id != "null") {
            $notinclass->where('s.m_id', '=', $m_id);
        }

        $data = $notinclass->get(['std_id', 'f_name', 'l_name', 'm.name', 'f.fac_id','f.name as fac_name'])->toArray();
        return datatables()->of($data)->make(true);
    }

    public function getSubject($id) // For Student
    {
        $data = DB::select("
        SELECT cr.cr_id,
        CONCAT(cr.s_id,' ',s.name,' ',year,'/',term) as full_subject_name 
        FROM course_registrations as cr 
        LEFT JOIN subjects as s ON cr.s_id = s.s_id
        WHERE cr.std_id = '$id'
        ORDER BY cr.s_id,cr.year,cr.term");
        return response()->json($data);
    }
}
