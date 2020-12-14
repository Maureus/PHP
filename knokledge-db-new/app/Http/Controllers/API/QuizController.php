<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse {
        return response()->json(Quiz::insertQuiz($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse {
        return response()->json(Quiz::selectById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse {
        return response()->json(Quiz::updateQuiz($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse {
        return response()->json(Quiz::deleteQuiz($id));
    }

    public function quizQuestions($id)
    {
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                Quiz::selectAllQuizQuestions($id),
                200);
        }

        return response()->json(
            null,
            400);
    }

    public function listQuizQuestionsByCategory($id)
    {
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

}
