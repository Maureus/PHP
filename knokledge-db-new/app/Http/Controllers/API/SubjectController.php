<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = DB::select("select * from SUBJECTS order by ID");
        return response()->json($result, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:subjects',
            'semester' => ['required', 'max:2', Rule::in('LS', 'ZS')],
            'year' => ['required', 'max:1', Rule::in('1', '2', '3', '4', '5')],
            'short_name' => 'required|max:5|unique:subjects',
            'subject_desc' => 'required|max:255'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        } else {
            $result = DB::insert(
                'insert into ' . Subject::SUBJECTS_TABLE . '( name, semester, year, short_name, subject_desc)
                values (:name, :semester, :year, :short_name, :subject_desc)',
                [
                    ':name' => $request->input('name'),
                    ':semester' => $request->input('semester'),
                    ':year' => $request->input('year'),
                    ':short_name' => $request->input('short_name'),
                    ':subject_desc' => $request->input('subject_desc')
                ]);

            if ($result == true) {
                return response()->json(1, 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = DB::selectOne("select * from SUBJECTS where ID = :id", [':id'=>$id]);
        return response()->json($result, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'semester' => ['required', 'max:2', Rule::in('LS', 'ZS')],
            'year' => ['required', 'max:1', Rule::in('1', '2', '3', '4', '5')],
            'short_name' => 'required|max:5',
            'subject_desc' => 'required|max:255'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        $result = DB::update(
            "update subjects set
                    name = :name,
                    semester = :semester,
                    year = :year,
                    SHORT_NAME = :short_name,
                    SUBJECT_DESC = :subject_desc,
                    UPDATED_AT = CURRENT_TIMESTAMP(6)
                    where ID = :id
                    ",
            [
                ':name' => $request->input('name'),
                ':semester' => $request->input('semester'),
                ':year' => $request->input('year'),
                ':short_name' => $request->input('short_name'),
                ':subject_desc' => $request->input('subject_desc'),
                ':id' => $id,
            ]);

        return $result == 1 ? response()->json($result, 200) : response()->json($result, 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = DB::delete("delete from SUBJECTS where ID = :id", [':id' => $id]);
        return $result == 1 ? response()->json($result, 200) : response()->json($result, 400);
    }
}
