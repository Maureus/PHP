<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    const SUBJECTS_TABLE = 'subjects';
    const SUBJECTS_VISIBLE = 'id, name, semester, year, short_name, subject_desc, created_at, updated_at';

    use HasFactory;

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
        'id',
        'name',
        'semester',
        'year',
        'short_name',
        'subject_desc',
        'created_at',
        'updated_at',
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    static public function selectAllSubjectUsers($id): array {
        return DB::select('select * from subject_users_view where subject_id = :id', [':id' => $id]);
    }

    static public function selectAllSubjectTeachers($id): array {
        return DB::select(
            'select * from subject_users_view where subject_id = :id and ROLE = :role',
            [':id' => $id, ":role" => 'teacher']
        );
    }

    static public function selectAll(): array {
        return DB::select("select * from SUBJECTS_VIEW order by id");
    }

    static public function selectById($id) {
        return DB::selectOne("select * from SUBJECTS_VIEW where id = :id",
            [':id' => $id]);
    }

    static public function deleteSubject($id) {
        $conn = oci_connect(DBC::DB_USERNAME, DBC::DB_PASSWORD, DBC::DB_CONNECTION_STRING);
        $sql = 'begin delete_subject(p_id => :id); end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, 255);
        oci_execute($stmt);
        oci_close($conn);
    }

    static public function insertSubject($request) {
        $conn = oci_connect(DBC::DB_USERNAME, DBC::DB_PASSWORD, DBC::DB_CONNECTION_STRING);
        $sql = 'begin insert_or_update_subject(
                           p_name => :name,
                           p_semester => :semester,
                           p_year => :year,
                           p_short_name => :short_name,
                           p_subject_desc => :subject_desc,
                           p_id_out => :v_id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $name = $request->name;
        $semester = $request->semester;
        $year = $request->year;
        $short_name = $request->short_name;
        $subject_desc = $request->subject_desc;

        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':semester', $semester, -1);
        oci_bind_by_name($stmt, ':year', $year, -1);
        oci_bind_by_name($stmt, ':short_name', $short_name, -1);
        oci_bind_by_name($stmt, ':subject_desc', $subject_desc, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function updateSubject($request, $id) {
        $conn = oci_connect(DBC::DB_USERNAME, DBC::DB_PASSWORD, DBC::DB_CONNECTION_STRING);
        $sql = 'begin insert_or_update_subject(p_id => :id,
                           p_name => :name,
                           p_semester => :semester,
                           p_year => :year,
                           p_short_name => :short_name,
                           p_subject_desc => :subject_desc,
                           p_id_out => :v_id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $name = $request->name;
        $semester = $request->semester;
        $year = $request->year;
        $short_name = $request->short_name;
        $subject_desc = $request->subject_desc;

        oci_bind_by_name($stmt, ':id', $id, -1);
        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':semester', $semester, -1);
        oci_bind_by_name($stmt, ':year', $year, -1);
        oci_bind_by_name($stmt, ':short_name', $short_name, -1);
        oci_bind_by_name($stmt, ':subject_desc', $subject_desc, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }
}
