<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse {
        return response()->json(Quiz::selectAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'date_from' => 'required',
            'date_till' => 'required',
            'quiz_desc' => 'max:255',
            'num_questions' => 'required',
            'points_fq' => 'required',
            'subject_id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        try {
            $result = Quiz::insertQuiz($request);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse {
        return response()->json(Quiz::selectById($id));
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
            'type' => 'required|max:255',
            'date_from' => 'required',
            'date_till' => 'required',
            'quiz_desc' => 'max:255',
            'num_questions' => 'required',
            'points_fq' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        try {
            $result = Quiz::updateQuiz($request, $id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse {
        try {
            Quiz::deleteQuiz($id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }

        return response()->json(1);
    }

    static public function showQuizQuestions($id): \Illuminate\Http\JsonResponse {
        try {
            $result = Quiz::selectAllQuizQuestions($id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }

        return response()->json($result);
    }

    public function listQuizQuestionsByCategory($id) {
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                Quiz::selectQuizQuestionsByCategory($id),
                200);
        }

        return response()->json(
            null,
            400);
    }

    static public function showQuizzesBySubjectID($id): \Illuminate\Http\JsonResponse {
        return response()->json(Quiz::selectSubjectQuizzes($id));
    }

    static public function assignAllQuestionToQuiz(Request $request, $id): \Illuminate\Http\JsonResponse {
        try {
            $result = Quiz::insertAllQuestionToQuiz($request, $id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return response()->json($result);
    }

    static public function showQuizResults($id): \Illuminate\Http\JsonResponse {
        try {
            $result = Quiz::selectQuizResults($id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return response()->json($result);
    }

    static public function showUserQuizzesResults($id): \Illuminate\Http\JsonResponse {
        try {
            $result = Quiz::selectUserResults($id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return response()->json($result);
    }

    static public function storeQuizResult(Request $request): \Illuminate\Http\JsonResponse {
        $validation = Validator::make($request->all(), [
            'quiz_id' => 'required',
            'result' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        try {
            $result = Quiz::insertQuizResult($request->quiz_id, $request->result);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return response()->json($result);
    }

    static public function assignQuestion($quizId, $questionId): \Illuminate\Http\JsonResponse {

        try {
            $result = Quiz::assignQuizQuestion($quizId, $questionId);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return $result ? response()->json(1) : response()->json(0);
    }

    static public function removeQuestion($quizId, $questionId): \Illuminate\Http\JsonResponse {

        try {
            $result = Quiz::removeQuizQuestion($quizId, $questionId);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return response()->json($result);
    }

}
