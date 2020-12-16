<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use PDO;
use yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class User extends Authenticatable
{
    const USERS_TABLE = 'users';
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

    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    public function subjects() {
        return $this->belongsToMany(Subject::class);
    }

    public function registerToCourse($course) {
        $this->courses()->save($course);
    }

    public function assignToSubject($subject) {
        $this->subjects()->save($subject);
    }

    static public function selectAll(): array {
        return DB::select("select * from users_view order by id");
    }

    static public function selectById($id) {
        return DB::selectOne("select * from users_view where id = :id",
            [':id' => $id]);
    }

    static public function insert(Request $request) {
        return DB::insert(
            "insert into " . User::USERS_TABLE . " (name, email, password, created_at) values (:v1, :v2, :v3, Current_Timestamp(6))",
            [':v1' => $request->input('name'), ':v2' => $request->input('email'), ':v3' => Hash::make($request->input('password'))]);
    }

    static public function selectAllUserSubjects($id) {
        return DB::select("select * from " . User::SUBJECTS_VIEW . " where user_id = :user_id order by id", [':user_id' => $id]);
    }

    static public function selectAllUserCourses($id) {
        return DB::select("select * from " . User::COURSES_VIEW . " where user_id = :user_id order by id", [':user_id' => $id]);
    }

    static public function selectAllUserQuizResults($id) {
        return DB::select(
            "select * from " . User::QUIZ_VIEW . " where user_id = :user_id order by id",
            [':user_id' => $id]
        );
    }

    static public function insertUser($name, $email, $password, $id) {
        $result = array();

        $conn = oci_connect(DBC::DB_USERNAME, DBC::DB_PASSWORD, DBC::DB_CONNECTION_STRING);
        $sql = 'begin insert_or_update_user(p_id => :id,
                           p_name => :name,
                           p_email => :email,
                           p_password => :password,
                           p_id_out => :v_id_out,
                           p_name_out => :v_name_out,
                           p_email_out => :v_email_out,
                           p_role_out => :v_role_out,
                           p_created_at_out => :v_created_at_out,
                           p_updated_at_out => :v_updated_at_out,
                           p_hasavatar => :v_hasavatar);
                        end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, -1);
        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':email', $email, -1);
        oci_bind_by_name($stmt, ':password', $password, -1);
        oci_bind_by_name($stmt, ':v_id_out', $result['id'], 255);
        oci_bind_by_name($stmt, ':v_name_out', $result['name'], 255);
        oci_bind_by_name($stmt, ':v_email_out', $result['email'], 255);
        oci_bind_by_name($stmt, ':v_role_out', $result['role'], 255);
        oci_bind_by_name($stmt, ':v_created_at_out', $result['created_at'], 255);
        oci_bind_by_name($stmt, ':v_updated_at_out', $result['updated_at'], 255);
        oci_bind_by_name($stmt, ':v_hasavatar', $result['hasavatar'], 255);
        oci_execute($stmt);
        oci_close($conn);

        return $result;
    }

    static public function deleteUser($id) {
        $conn = oci_connect(DBC::DB_USERNAME, DBC::DB_PASSWORD, DBC::DB_CONNECTION_STRING);
        $sql = 'begin delete_user(p_id => :id); end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, 255);
        oci_execute($stmt);
        oci_close($conn);
    }

    static public function updateUserChangeNamePhoneAddressAvatar($request) {
        $request->file('avatar')->storeAs('public/avatars', 'avatar' . Auth::id() . '.jpg');

        $pdo = DB::getPdo();

        $statement = $pdo->prepare('update users set name = ?, PHONE = ?, ADDRESS = ?, AVATAR = ?, UPDATED_AT = CURRENT_TIMESTAMP(6), HASAVATAR = 1 where id = ?');

        $statement->bindValue(1, $request->name, PDO::PARAM_STR);
        $statement->bindValue(2, $request->phone, PDO::PARAM_STR);
        $statement->bindValue(3, $request->address, PDO::PARAM_STR);
        $statement->bindValue(4, file_get_contents($request->file("avatar")), PDO::PARAM_LOB);
        $statement->bindValue(5, $request->id, PDO::PARAM_INT);
        $statement->execute();
    }

    static public function updateUserChangeNamePhoneAddress($request): int {
        return DB::update(
            'update users set name = :name, phone = :phone, address = :address where id = :id',
            [
                ':name' => $request->name,
                ':phone' => $request->phone,
                ':address' => $request->address,
                ':id' => $request->id
            ]
        );
    }

    static public function updateUser($request, $id): int {

        return $result = DB::update(
            'update users set name = :name, email = :email, ROLE = :role,
                 PHONE = :phone, ADDRESS = :address where ID = :id',
            [
                ':name' => $request->name,
                ':email' => $request->email,
                ':phone' => $request->phone,
                ':address' => $request->address,
                ':role' => $request->role,
                ':id' => $id,
            ]
        );
    }

}
