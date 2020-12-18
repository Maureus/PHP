<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Question::selectAllQuestions());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'answer_1' => 'required|max:255',
            'answer_2' => 'required|max:255',
            'answer_correct' => 'required|max:255',
            'category_id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        try {
            $result = Question::insertQuestion($request);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json(Question::selectQuestion($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'answer_1' => 'required|max:255',
            'answer_2' => 'required|max:255',
            'answer_correct' => 'required|max:255',
            'category_id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        try {
            $result = Question::updateQuestion($request, $id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Question::deleteQuestion($id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return response()->json(1);
    }

    static public function storeQuestionInQuiz(Request $request, $id): \Illuminate\Http\JsonResponse {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'answer_1' => 'required|max:255',
            'answer_2' => 'required|max:255',
            'answer_correct' => 'required|max:255',
            'category_id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        try {
            $result = Question::insertQuestionInQuiz($request, $id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }

        return response()->json($result);
    }
}
