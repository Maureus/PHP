<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;
use function PHPUnit\Framework\throwException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    static public function index(): \Illuminate\Http\JsonResponse {
        return response()->json(User::selectAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    static public function store(Request $request): \Illuminate\Http\JsonResponse {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required|min:6'
        ]);

        $name = $request->input("name");
        $email = $request->input("email");
        $password = Hash::make($request->input("password"));
        $id = $request->input("id");

        try {
            $result = User::insertUser($name, $email, $password, $id);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
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
        return response()->json(User::selectById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    static public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'role' => ['required', Rule::in('student', 'admin', 'teacher')]
        ]);

        try {
            $result = User::updateUser($request, $id);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    static public function destroy($id) {
        try {
            User::deleteUser($id);
        } catch (\Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }
        return response()->json(1);
    }

    static public function userSubjects($id) {
        return response()->json(User::selectAllUserSubjects($id));
    }

    static public function userCourses($id) {
        return response()->json(User::selectAllUserCourses($id));
    }

    static public function userQuizResults($id) {
        return response()->json(User::selectAllUserQuizResults($id));
    }

    static public function updateUserProfile(Request $request) {
        try {
            if (isset($request->password) && !isset($request->name)) {
                $request->validate([
                    'password' => 'required|min:6|confirmed',
                ]);

                return response()->json(User::updateUserChangePassword($request));

            } else if (
                isset($request->name) &&
                isset($request->phone) &&
                isset($request->address) &&
                isset($request->avatar) &&
                !isset($request->password)
            ) {
                $request->validate([
                    'name' => 'required|max:255',
                    'phone' => 'max:255',
                    'address' => 'max:255'
                ]);

                try {
                    User::updateUserChangeNamePhoneAddressAvatar($request);
                } catch (\Exception $ex) {
                    return response()->json($ex->getMessage(), 400);
                }

                return response()->json(1);

            } else if (
                isset($request->name) &&
                isset($request->phone) &&
                isset($request->address) &&
                !isset($request->avatar) &&
                !isset($request->password)
            ) {
                $request->validate([
                    'name' => 'required|max:255',
                    'phone' => 'max:255',
                    'address' => 'max:255'
                ]);

                return response()->json(User::updateUserChangeNamePhoneAddress($request));

            } else {
                $request->validate([
                    'name' => 'required|max:255',
                    'phone' => 'max:255',
                    'address' => 'max:255',
                    'password' => 'required|min:6|confirmed',
                ]);

                try {
                    User::updateUserChangeNamePhoneAddressAvatarPassword($request);
                } catch (\Exception $ex) {
                    return response()->json(0, 400);
                }

                return response()->json(1);
            }
        } catch (QueryException | FileNotFoundException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    static public function assignSubjectToUser($id1, $id2) {
        $result = DB::insert(
            "insert into SUBJECT_USER values(:subjectID, :userID)",
            [
                ':subjectID' => $id2,
                ':userID' => $id1
            ]
        );

        return $result ? response()->json(1) : response()->json(0, 400);
    }

    static public function removeSubjectFromUser($id1, $id2) {
        $result = DB::delete(
            "delete from SUBJECT_USER where SUBJECT_ID=:subjectID and USER_ID = :userID",
            [
                ':subjectID' => $id2,
                ':userID' => $id1
            ]
        );

        return $result == 1 ? response()->json($result) : response()->json($result, 400);
    }
}
