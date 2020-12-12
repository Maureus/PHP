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

    public function users(){
        return $this->belongsToMany(User::class);
    }

    static public function selectAllSubjectUsers($id): array {
        return DB::select('select * from subject_users_view where subject_id = :id', [':id'=>$id]);
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
}
