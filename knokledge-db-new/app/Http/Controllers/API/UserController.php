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
    static public function store(Request $request) {
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
            return response()->json(0, 400);
        }

        return response()->json(
            $result,
            200);
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

        return response()->json(User::updateUser($request, $id));
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
            return response()->json(0, 400);
        }
        return response()->json(1);
    }

    static public function userSubjects($id) {
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                User::selectAllUserSubjects($id),
                200);
        }

        return response()->json(
            null,
            400);
    }

    static public function userCourses($id) {
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                User::selectAllUserCourses($id),
                200);
        }

        return response()->json(
            null,
            400);
    }

    static public function userQuizResults($id) {
        if (is_numeric($id) && !empty($id)) {
            return response()->json(
                User::selectAllUserQuizResults($id),
                200);
        }

        return response()->json(
            null,
            400);
    }

    static public function updateUserProfile(Request $request) {
        try {
            if (isset($request->password) && !isset($request->name)) {
                $request->validate([
                    'password' => 'required|min:6|confirmed',
                ]);
                User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);

                return response()->json(
                    4,
                    200);
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

                User::updateUserChangeNamePhoneAddressAvatar($request);

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

                $request->file('avatar')->storeAs('public/avatars', 'avatar' . Auth::id() . '.jpg');
                $pdo = DB::getPdo();

                $statement = $pdo->prepare(
                    'update users set name = ?, PHONE = ?, ADDRESS = ?, AVATAR = ?,
                 PASSWORD = ?, UPDATED_AT = CURRENT_TIMESTAMP(6), HASAVATAR = 1 where id = ?'
                );

                $statement->bindValue(1, $request->name, PDO::PARAM_STR);
                $statement->bindValue(2, $request->phone, PDO::PARAM_STR);
                $statement->bindValue(3, $request->address, PDO::PARAM_STR);
                $statement->bindValue(4, file_get_contents($request->file("avatar")), PDO::PARAM_LOB);
                $statement->bindValue(5, Hash::make($request->password), PDO::PARAM_STR);
                $statement->bindValue(6, $request->id, PDO::PARAM_INT);
                $statement->execute();

                return response()->json(
                    3,
                    200);

            }
        } catch (QueryException | FileNotFoundException $e) {
            return response()->json(
                null,
                400);
        }
    }

    static public function assignSubjectToUser($id1, $id2) {
        try {
            $result = DB::insert(
                "insert into SUBJECT_USER values(:subjectID, :userID)",
                [':subjectID' => $id2, ':userID' => $id1]);
        } catch (\Exception $e) {
            return response()->json(0, 400);
        }

        return $result ? response()->json(null, 200) : response()->json(null, 400);
    }

    static public function removeSubjectFromUser($id1, $id2) {
        $result = DB::delete(
            "delete from SUBJECT_USER where SUBJECT_ID=:subjectID and USER_ID = :userID",
            [':subjectID' => $id2, ':userID' => $id1]);

        return $result == 1 ? response()->json($result, 200) : response()->json($result, 400);
    }
}
