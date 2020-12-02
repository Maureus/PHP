<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\Stud_matController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\UserController;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\RegisterController;
use \App\Http\Controllers\LoginController;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Cookie auth (sanctum)
Route::post('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// For token auth
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Users
Route::get('/users/{id}/subjects', function ($id) {
    return UserController::userSubjects($id);
})->where('id', '[0-9]+');
Route::get('/users/{id}/courses', function ($id) {
    return UserController::userCourses($id);
})->where('id', '[0-9]+');
Route::get('/users/{id}/quizzes', function ($id) {
    return UserController::userQuizResults($id);
})->where('id', '[0-9]+');


Route::apiResources([
    'users' => UserController::class,
    'subjects' => SubjectController::class,
    'courses' => CourseController::class,
    'questions' => QuestionController::class,
    'quizzes' => QuizController::class,
    'study_mats' => Stud_matController::class,
    'categories' => CategoryController::class,
    'comments' => CommentController::class
]);

//Subjects

//Courses

//Categories

//Comments

//Questions

//Quizzes

//Study_mats


Route::post('/users/test/', [UserController::class, 'updateUserProfile']);

//Route::get('/useravatar/{id}', function ($id){
//    $file = DB::selectOne('Select avatar from users where id = :id', [":id"=>$id]);
//    //$data = base64_encode($file);
//    return response()->file($file);
//});

// Get image on server by url
Route::get('image/{file_name}', function($filename){
    $path = storage_path("app/public/$filename");
    $image = File::get($path);
    $mime = File::mimeType($path);
    return response($image, 200)->header('Content-Type', $mime);
});


// TODO check if working properly
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function (){
    //Logged in user
    Route::get('/user', function () {
        return request()->user();
    });

    Route::post('/saveuser', [UserController::class, 'updateUserProfile']);

//    Route::post('/saveuser', function(Request $request)
//    {
//        $pdo = DB::getPdo();
//
//        $statement = $pdo->prepare('update users set name = ?, PHONE = ?, ADDRESS = ?, AVATAR = ? where id = ?');
//
////        $file = $request->file("avatar");
////        $data = fopen ($file, 'rb');
////        $size = filesize ($file);
////        $contents = fread ($data, $size);
////        fclose ($data);
//
//
//
////        $id = Auth::user()->id;
//        $statement->bindValue(1, $request->name, PDO::PARAM_STR);
//        $statement->bindValue(2, $request->phone, PDO::PARAM_STR);
//        $statement->bindValue(3, $request->address, PDO::PARAM_STR);
//        $statement->bindValue(4, file_get_contents($request->file("avatar")), PDO::PARAM_LOB);
//        $statement->bindValue(5, $request->id, PDO::PARAM_INT);
//        $statement->execute();
//    });

    //For token auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});


