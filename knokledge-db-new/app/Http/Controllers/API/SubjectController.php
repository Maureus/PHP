<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Subject;
use Illuminate\Http\Request;
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
        try {
            $result = Subject::selectAll();
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json($result);
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

        try {
            $result = Subject::insertSubject($request);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    static public function show($id): \Illuminate\Http\JsonResponse {
        try {
            $result = Subject::selectById($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse {
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

        try {
            $result = Subject::updateSubject($request, $id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    static public function destroy($id): \Illuminate\Http\JsonResponse {
        try {
            Subject::deleteSubject($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json(1);
    }

    static public function subjectUsers($id): \Illuminate\Http\JsonResponse {
        try {
            $result = Subject::selectAllSubjectUsers($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json($result);
    }

    static public function subjectTeachers($id): \Illuminate\Http\JsonResponse {
        try {
            $result = Subject::selectAllSubjectTeachers($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json($result);
    }

    static public function subjectStudents($id): \Illuminate\Http\JsonResponse {
        try {
            $result = Subject::selectAllSubjectStudents($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json($result);
    }

    static public function subjectComments($id): \Illuminate\Http\JsonResponse {
        try {
            $result = Comment::selectAllSubjectComments($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json($result);
    }

}
