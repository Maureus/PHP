<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index()
    {
        return response()->json(
            User::selectAll(),
         200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (isset($request->user)) {
            $user = $request->user;
            var_dump($user);
            User::save($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    static public function show($id)
    {
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                User::selectById($id),
                200);
        }

        return response()->json(
            null,
            400);
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
        try {
            if (isset($request->password) && !isset($request->name)) {
                $request->validate([
                    'password' => 'required|min:6|confirmed',
                ]);
                User::where('id', $id)->update(['password'=> Hash::make($request->password)]);
            } else if (
                isset($request->name) &&
                isset($request->phone) &&
                isset($request->address) &&
                !isset($request->password)
            ) {
                $request->validate([
                    'name' => 'required|max:255',
                    'phone' => 'max:255',
                    'address' => 'max:255'
                ]);
                User::where('id', $id)
                    ->update([
                        'name'=>$request->name,
                        'avatar'=>$request->file('avatar'),
                        'phone' => $request->phone,
                        'address' => $request->address
                    ]);
                return response()->json(
                    $request->avatar,
                    200);
            } else {
                $request->validate([
                    'name' => 'required|max:255',
                    'phone' => 'max:255',
                    'address' => 'max:255',
                    'password' => 'required|min:6|confirmed',
                ]);

                User::where('id', $id)
                    ->update([
                        'name'=>$request->name,
                        'avatar'=>$request->file('avatar'),
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'password' => Hash::make($request->password)
                    ]);
                return response()->json(
                    3,
                    200);

            }
            return response()->json(
                null,
                200);
        } catch (QueryException $e) {
            return response()->json(
                null,
                400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
    }

    static public function userSubjects($id){
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                User::selectAllUserSubjects($id),
                200);
        }

        return response()->json(
            null,
            400);
    }

    static public function userCourses($id){
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                User::selectAllUserCourses($id),
                200);
        }

        return response()->json(
            null,
            400);
    }

    static public function userQuizResults($id){
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                User::selectAllUserQuizResults($id),
                200);
        }

        return response()->json(
            null,
            400);
    }

    static public function updateUserProfile(Request $request)
    {
        try {
            if (isset($request->password) && !isset($request->name)) {
                $request->validate([
                    'password' => 'required|min:6|confirmed',
                ]);
                User::where('id', $request->id)->update(['password'=> Hash::make($request->password)]);
            } else if (
                isset($request->name) &&
                isset($request->phone) &&
                isset($request->address) &&
                !isset($request->password)
            ) {
                $request->validate([
                    'name' => 'required|max:255',
                    'phone' => 'max:255',
                    'address' => 'max:255'
                ]);
                User::where('id', $request->id)
                    ->update([
                        'name'=>$request->name,
                        'avatar'=>$request->file('avatar'),
                        'phone' => $request->phone,
                        'address' => $request->address
                    ]);
                return response()->json(
                    $request->avatar,
                    200);
            } else {
                $request->validate([
                    'name' => 'required|max:255',
                    'phone' => 'max:255',
                    'address' => 'max:255',
                    'password' => 'required|min:6|confirmed',
                ]);

                User::where('id', $request->id)
                    ->update([
                        'name'=>$request->name,
                        'avatar'=>$request->file('avatar')->get(),
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'password' => Hash::make($request->password)
                    ]);
                return response()->json(
                    $request->avatar,
                    200);

            }
            return response()->json(
                $request->avatar,
                200);
        } catch (QueryException $e) {
            return response()->json(
                null,
                400);
        } catch (FileNotFoundException $e) {
            return response()->json(
                null,
                400);
        }

    }
}
