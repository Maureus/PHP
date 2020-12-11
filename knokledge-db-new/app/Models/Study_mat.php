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

    static public function getAll(): array {
        return DB::select(
            "select id, name, FILE_NAME, FILE_TYPE, DATE_FROM,
            DATE_TILL, CREATED_BY, EDITED_BY, CREATED_AT, UPDATED_AT from STUDY_MATS order by ID"
        );
    }

    static public function getByID($id) {
        return DB::selectOne(
            "select id, name, FILE_NAME, FILE_TYPE, DATE_FROM,
            DATE_TILL, CREATED_BY, EDITED_BY, CREATED_AT, UPDATED_AT from STUDY_MATS where id = :id order by ID",
            [':id'=>$id]) ;
    }

    static public function insert($request) {
        $file = $request->file('file');
        $pdo = DB::getPdo();

        $statement = $pdo->prepare(
            'insert into STUDY_MATS (
                        NAME, FILE_NAME, FILE_TYPE, ST_MAT, DATE_FROM, DATE_TILL, CREATED_BY, SUBJECT_ID
                        ) values (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $created_by = "test";

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
        $name = DB::selectOne('select file_name from STUDY_MATS where id = :id', [':id'=>$request->id]);
        Storage::delete('public/files/'.$name->file_name);

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
        $created_by = 'Test';

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

    static public function deleteById($id): int {
        $name = DB::selectOne('select file_name from STUDY_MATS where id = :id', [':id'=>$id]);
        Storage::delete('public/files/'.$name->file_name);
        return DB::delete("delete from STUDY_MATS where id = :id", [':id'=>$id]);
    }
}
