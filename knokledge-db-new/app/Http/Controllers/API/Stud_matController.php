<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Study_mat;
use Illuminate\Http\Request;

class Stud_matController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        return response()->json(Study_mat::getAll(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        $result = Study_mat::insert($request);
        return response()->json($result, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        return response()->json(Study_mat::getByID($id), 200);
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
    public function destroy($id) {
        return response()->json(Study_mat::deleteById($id), 200);
    }

    static public function updateSM(Request $request) {
        Study_mat::updateById($request);
        return response()->json(1, 200);
    }
}
