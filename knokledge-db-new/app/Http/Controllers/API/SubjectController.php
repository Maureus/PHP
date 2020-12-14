<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use http\Env\Response;
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
    static public function index(): \Illuminate\Http\JsonResponse {
        return response()->json(Subject::selectAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    static public function store(Request $request): \Illuminate\Http\JsonResponse {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:subjects',
            'semester' => ['required', 'max:2', Rule::in('LS', 'ZS')],
            'year' => ['required', 'max:1', Rule::in('1', '2', '3', '4', '5')],
            'short_name' => 'required|max:5|unique:subjects',
            'subject_desc' => 'required|max:255'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        return response()->json(Subject::insertSubject($request));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    static public function show($id): \Illuminate\Http\JsonResponse {
        return response()->json(Subject::selectById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id) {
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

        return response()->json(Subject::updateSubject($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    static public function destroy($id) {
        try {
            Subject::deleteSubject($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json(1);
    }

    static public function subjectUsers($id) {
        return response()->json(Subject::selectAllSubjectUsers($id));
    }

    static public function subjectTeachers($id) {
        return response()->json(Subject::selectAllSubjectTeachers($id));
    }


}
