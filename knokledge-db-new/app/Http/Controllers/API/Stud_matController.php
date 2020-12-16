<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Study_mat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Stud_matController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse {
        return response()->json(Study_mat::selectAll());
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
            'file' => 'required',
            'date_from' => 'required',
            'date_till' => 'required',
            'subject_id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        $result = Study_mat::insertSM($request);
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse {
        return response()->json(Study_mat::selectByID($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse {
        try {
            Study_mat::deleteSM($id);
        } catch (\Exception $ex) {
            return response()->json(0, 400);
        }
        return response()->json(1);
    }

    static public function updateSM(Request $request) {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'date_from' => 'required',
            'date_till' => 'required',
            'id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(0, 400);
        }

        return response()->json(Study_mat::updateById($request));
    }

    static public function showSMBySubjectID($id): \Illuminate\Http\JsonResponse {
        return response()->json(Study_mat::selectSubjectSM($id));
    }
}
