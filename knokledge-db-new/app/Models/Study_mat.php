<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDO;

class Study_mat extends Model
{
    use HasFactory;

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
        'file_name',
        'file_type',
        'file',
        'date_from',
        'date_till',
        'created_by',
        'edited_by',
        'created_at',
        'updated_at',
        'course_id',
    ];

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    static public function selectAll(): array {
        return DB::select("select * from STUDY_MATS_VIEW order by ID");
    }

    static public function selectByID($id) {
        return DB::selectOne("select * from STUDY_MATS_VIEW where id = :id",
            [':id' => $id]);
    }

    static public function insert($request) {
        $file = $request->file('file');
        $pdo = DB::getPdo();

        $statement = $pdo->prepare(
            'insert into STUDY_MATS (NAME, FILE_NAME, FILE_TYPE, ST_MAT, DATE_FROM, DATE_TILL, CREATED_BY, SUBJECT_ID)
            values (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $created_by = Auth::user()->getAuthIdentifierName();

        $statement->bindValue(1, $request->name, PDO::PARAM_STR);
        $statement->bindValue(2, $file->getClientOriginalName(), PDO::PARAM_STR);
        $statement->bindValue(3, $file->getClientOriginalExtension(), PDO::PARAM_STR);
        $statement->bindValue(4, file_get_contents($file), PDO::PARAM_LOB);
        $statement->bindValue(5, $request->date_from);
        $statement->bindValue(6, $request->date_till);
        $statement->bindValue(7, $created_by, PDO::PARAM_STR);
        $statement->bindValue(8, $request->subject_id, PDO::PARAM_INT);
        $statement->execute();

        $file->storeAs('public/files', $file->getClientOriginalName());
        return 1;
    }

    static public function updateById($request) {
        $name = DB::selectOne('select file_name from STUDY_MATS_VIEW where id = :id', [':id' => $request->id]);
        Storage::delete('public/files/' . $name->file_name);

        $file = $request->file('file');
        $pdo = DB::getPdo();

        $statement = $pdo->prepare(
            'update STUDY_MATS set
                      NAME = ?,
                      FILE_NAME = ?,
                      FILE_TYPE = ?,
                      ST_MAT = ?,
                      DATE_FROM = ?,
                      DATE_TILL = ?,
                      EDITED_BY = ?,
                      UPDATED_AT = CURRENT_TIMESTAMP(6) where id = ?'
        );
        $created_by = Auth::user()->getAuthIdentifierName();

        $statement->bindValue(1, $request->name, PDO::PARAM_STR);
        $statement->bindValue(2, $file->getClientOriginalName(), PDO::PARAM_STR);
        $statement->bindValue(3, $file->getClientOriginalExtension(), PDO::PARAM_STR);
        $statement->bindValue(4, file_get_contents($file), PDO::PARAM_LOB);
        $statement->bindValue(5, $request->date_from);
        $statement->bindValue(6, $request->date_till);
        $statement->bindValue(7, $created_by, PDO::PARAM_STR);
        $statement->bindValue(8, $request->id, PDO::PARAM_STR);
        $statement->execute();

        $file->storeAs('public/files', $file->getClientOriginalName());
    }

    static public function insertFromProc($request): int {
        $result = 0;
        $file = $request->file('file');
        $id = null;

        $conn = oci_connect('ST58211', 'Andr7265357', '//fei-sql1.upceucebny.cz:1521/IDAS.UPCEUCEBNY.CZ');
        $sql = 'begin insert_or_update_study_mat(
                            p_id=> :id,
                           p_name => :name,
                           p_file_name => :file_name,
                           p_file_type => :file_type,
                           p_st_mat => :st_mat,
                           p_date_from => :date_from,
                           p_date_till => :date_till,
                           p_created_by => :created_by,
                           p_subject_id => :subject_id,
                           p_id_out => :id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $fileContent = file_get_contents($file);
        $created_by = 'Test';
        $name = $request->name;
        $blob = oci_new_descriptor($conn, OCI_DTYPE_LOB);
        $dateFrom = $request->date_from;
        $dateTill = $request->date_till;
        $subjectID = $request->subject_id;
        $fileName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();


        oci_bind_by_name($stmt, ':id', $id, -1);
        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':file_name', $fileName, -1);
        oci_bind_by_name($stmt, ':file_type', $fileType, -1);
        oci_bind_by_name($stmt, ':st_mat', $blob, -1, OCI_B_BLOB);
        oci_bind_by_name($stmt, ':date_from', $dateFrom, -1);
        oci_bind_by_name($stmt, ':date_till', $dateTill, -1);
        oci_bind_by_name($stmt, ':created_by', $created_by, -1);
        oci_bind_by_name($stmt, ':subject_id', $subjectID, -1);
        oci_bind_by_name($stmt, ':id_out', $result, 255);



        $blob->write($fileContent);
        oci_execute($stmt);
        oci_commit($conn);

        $blob->free();
        oci_free_statement($stmt);
        oci_close($conn);

        return $result;
    }

    static public function deleteSM($id) {
        $name = DB::selectOne('select file_name from STUDY_MATS_VIEW where id = :id', [':id' => $id]);
        Storage::delete('public/files/' . $name->file_name);
        $conn = oci_connect(DBC::DB_USERNAME, DBC::DB_PASSWORD, DBC::DB_CONNECTION_STRING);
        $sql = 'begin delete_study_mat(p_id => :id); end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, 255);
        oci_execute($stmt);
        oci_close($conn);
    }

    static public function selectSubjectSM($id) {
        return DB::select("select * from STUDY_MATS_VIEW where SUBJECT_ID = :id order by ID",
            [':id' => $id]);
    }
}
