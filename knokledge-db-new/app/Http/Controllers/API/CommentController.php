<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse {
        return response()->json(Comment::selectAllComments());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse {
        $validation = Validator::make($request->all(), [
            'text' => 'required|max:255',
            'comment_id' => '',
            'subject_id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        try {
            $result = Comment::insertComment($request);
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
    public function show($id): \Illuminate\Http\JsonResponse {
        return response()->json(Comment::selectComment($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse {
        $validation = Validator::make(
            $request->all(),
            [
                'text' => 'required|max:255'
            ]
        );

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        try {
            $result = Comment::updateComment($request, $id);
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
    public function destroy($id): \Illuminate\Http\JsonResponse {
        try {
            Comment::deleteComment($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json(1);
    }

}
