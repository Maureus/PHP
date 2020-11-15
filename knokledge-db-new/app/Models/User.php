<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class User extends Authenticatable
{
    const USERS_TABLE = 'users';
    const USERS_VISIBLE = 'id, name, email, created_at, updated_at, role, avatar';
    const COURSES_VIEW = 'user_courses_view';
    const SUBJECTS_VIEW = 'user_subjects_view';
    const QUIZ_VIEW = 'quiz_user_result_view';

    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'role',
        'created_at',
        'updated_at',
        'phone',
        'address',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'avatar'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses(){
        return $this->belongsToMany(Course::class);
    }

    public function subjects(){
        return $this->belongsToMany(Subject::class);
    }

    public function registerToCourse($course){
        $this->courses()->save($course);
    }

    public function assignToSubject($subject){
        $this->subjects()->save($subject);
    }

    static public function selectAll() {
        return DB::select("select ".User::USERS_VISIBLE." from " . User::USERS_TABLE." order by id");
    }

    static public function selectById($id) {
        return DB::selectOne("select ".User::USERS_VISIBLE." from " . User::USERS_TABLE." where id = :id",
            [':id' => $id]);
    }

    static public function insert(Request $request) {
        return DB::insert(
            "insert into ".User::USERS_TABLE." (name, email, password, created_at) values (:v1, :v2, :v3, Current_Timestamp(6))",
            [':v1' => $request->input('name'), ':v2'=>$request->input('email'), ':v3'=>Hash::make($request->input('password'))]);
    }

    static public function selectAllUserSubjects($id) {
        return DB::select("select * from ".User::SUBJECTS_VIEW." where user_id = :user_id order by id", [':user_id'=>$id]);
    }

    static public function selectAllUserCourses($id) {
        return DB::select("select * from " . User::COURSES_VIEW." where user_id = :user_id order by id", [':user_id'=>$id]);
    }

    static public function selectAllUserQuizResults($id){
        return DB::select("select * from " . User::QUIZ_VIEW." where user_id = :user_id order by id", [':user_id'=>$id]);
    }

}
