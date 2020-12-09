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
        'avatar',
        'hasAvatar'
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
        return DB::select(
            "select * from " . User::QUIZ_VIEW." where user_id = :user_id order by id",
            [':user_id'=>$id]
        );
    }

    static public function insertUser($name, $email, $password, $id) {
        $result = array();

        $conn = oci_connect('ST58211', 'Andr7265357', '//fei-sql1.upceucebny.cz:1521/IDAS.UPCEUCEBNY.CZ');
        $sql = 'begin insert_or_update_user(p_id => :id,
                           p_name => :name,
                           p_email => :email,
                           p_password => :password,
                           p_id_out => :v_id_out,
                           p_name_out => :v_name_out,
                           p_email_out => :v_email_out,
                           p_role_out => :v_role_out,
                           p_created_at_out => :v_created_at_out,
                           p_updated_at_out => :v_updated_at_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, 255);
        oci_bind_by_name($stmt, ':name', $name, 255);
        oci_bind_by_name($stmt, ':email', $email, 255);
        oci_bind_by_name($stmt, ':password', $password, 255);
        oci_bind_by_name($stmt, ':v_id_out', $result['id'], 255);
        oci_bind_by_name($stmt, ':v_name_out', $result['name'], 255);
        oci_bind_by_name($stmt, ':v_email_out', $result['email'], 255);
        oci_bind_by_name($stmt, ':v_role_out', $result['role'], 255);
        oci_bind_by_name($stmt, ':v_created_at_out', $result['created_at'], 255);
        oci_bind_by_name($stmt, ':v_updated_at_out', $result['updated_at'], 255);
        oci_execute($stmt);
        oci_close($conn);

        return $result;
    }

}
