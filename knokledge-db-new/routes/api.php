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


// Cookie auth (sanctum)
Route::post('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// For token auth
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


// MIDDLEWARE FOR USER AUTH
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    //Logged in user
    Route::get('/user', function () {
        return request()->user();
    });

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

    //subject quizzes
    Route::get('/subject/{id}/quizzes', function ($id) {
        return QuizController::showQuizzesBySubjectID($id);
    })->whereNumber('id');

    //Study_mats
    Route::get('/subject/{id}/study_mats', function ($id) {
        return Stud_matController::showSMBySubjectID($id);
    })->whereNumber('id');

    Route::post('/users/test/', [UserController::class, 'updateUserProfile']);

    // Get image on server by url
    Route::get('image/{file_name}', function ($filename) {
        $path = storage_path("app/public/avatars/$filename");
        $image = File::get($path);
        $mime = File::mimeType($path);
        return response($image, 200)->header('Content-Type', $mime);
    });

    // Get
    Route::get('file/{file_name}', function ($filename) {
        $path = storage_path("app/public/files/$filename");
        $file = File::get($path);
        $mime = File::mimeType($path);
        return response($file, 200)->header('Content-Type', $mime);
    });

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


// assign and delete Subject to user
    Route::post('/users/{id1}/subjects/{id2}', function ($id1, $id2) {
        return UserController::assignSubjectToUser($id1, $id2);
    })->where(['id1' => '[0-9]+', 'id2' => '[0-9]+']);

    Route::delete('/users/{id1}/subjects/{id2}', function ($id1, $id2) {
        return UserController::removeSubjectFromUser($id1, $id2);
    })->where(['id1' => '[0-9]+', 'id2' => '[0-9]+']);

// select all assigned to subject users
    Route::get('/subject/{id}/users', function ($id) {
        return SubjectController::subjectUsers($id);
    })->whereNumber('id');

    Route::post('/saveuser', [UserController::class, 'updateUserProfile']);

    //For token auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // select all teacher assigned to subject
    Route::get('/subject/{id}/teachers', function ($id) {
        return SubjectController::subjectTeachers($id);
    })->whereNumber('id');

// select all students assigned to subject
    Route::get('/subject/{id}/students', function ($id) {
        return SubjectController::subjectStudents($id);
    })->whereNumber('id');

    Route::post('/study_mats/update', [Stud_matController::class, 'updateSM']);

//subject all subject comments, order by CREATED_AT desc
    Route::get('/subject/{id}/comments', function ($id) {
        return SubjectController::subjectComments($id);
    })->whereNumber('id');

// post question to quiz
    Route::post('/quiz/{id}/question', [QuestionController::class, 'storeQuestionInQuiz'])->whereNumber('id');

// get quiz questions
    Route::get('/quiz/{id}/questions', [QuizController::class, 'showQuizQuestions'])->whereNumber('id');

// assign array of question ids to quiz
    Route::post('/quiz/{id}/questions', [QuizController::class, 'assignAllQuestionToQuiz'])->whereNumber('id');

// get quiz results
    Route::get('/quiz/{id}/results', [QuizController::class, 'showQuizResults'])->whereNumber('id');

// get user quizzes results
    Route::get('/user/{id}/results', [QuizController::class, 'showUserQuizzesResults'])->whereNumber('id');

// store user quiz result
    Route::post('/quiz/results', [QuizController::class, 'storeQuizResult']);

    Route::post('login/emulate/{id}', [LoginController::class, 'emulateUser'])->whereNumber('id');
    Route::post('login/emulate/cancel', [LoginController::class, 'cancelEmulation']);

// add or remove question
    Route::post('/quiz/{quizId}/question/{questionId}', function ($quizId, $questionId) {
        return QuizController::assignQuestion($quizId, $questionId);
    })->where(['quizId' => '[0-9]+', 'questionId' => '[0-9]+']);

    Route::delete('/quiz/{quizId}/question/{questionId}', function ($quizId, $questionId) {
        return QuizController::removeQuestion($quizId, $questionId);
    })->where(['quizId' => '[0-9]+', 'questionId' => '[0-9]+']);

// get subject questions
    Route::get('/subject/{id}/questions', [QuestionController::class, 'showSubjectQuestions'])
        ->whereNumber('id');
});


