<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Comment extends Model
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
        'text',
        'created_at',
        'updated_at',
        'user_id',
        'comment_id',
        'course_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment() {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Course::class, 'course_id');
    }

    static public function selectSubCommentsTreeStartingFromId($id): array {
        return DB::select(
            "select * from COMMENTS_VIEW START WITH id = :id CONNECT BY PRIOR id = comment_id",
            [':id' => $id]
        );
    }

    static public function insertComment($request) {
        $conn = DBC::getConnection();
        $sql = 'begin insert_or_update_comment(
                           p_text => :text,
                           p_comment_id => :comment_id,
                           p_user_id => :user_id,
                           p_subject_id => :subject_id,
                           p_id_out => :v_id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $text = $request->text;
        $comment_id = $request->comment_id;
        $user_id = Auth::id();
        $subject_id = $request->subject_id;

        oci_bind_by_name($stmt, ':text', $text, -1);
        oci_bind_by_name($stmt, ':comment_id', $comment_id, -1);
        oci_bind_by_name($stmt, ':user_id', $user_id, -1);
        oci_bind_by_name($stmt, ':subject_id', $subject_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function updateComment($request, $id) {
        $conn = DBC::getConnection();
        $sql = 'begin insert_or_update_comment(p_id => :id, p_text => :text, p_id_out => :v_id_out); end;';
        $stmt = oci_parse($conn, $sql);
        $text = $request->text;

        oci_bind_by_name($stmt, ':id', $id, -1);
        oci_bind_by_name($stmt, ':text', $text, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function deleteComment($id) {
        $conn = DBC::getConnection();
        $sql = 'begin delete_comment(p_id => :id); end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, -1);
        oci_execute($stmt);
        oci_close($conn);
    }

    static public function selectComment($id) {
        return DB::selectOne("select * from COMMENTS_VIEW where id = :id", [':id' => $id]);
    }

    static public function selectAllComments(): array {
        return DB::select("select * from COMMENTS_VIEW order by id");
    }

    static public function selectAllSubjectComments($id): array {
        return DB::select(
            "select id, TEXT, CREATED_AT, UPDATED_AT, COMMENT_ID, USER_ID, USER_NAME, USER_EMAIL, SUBJECT_ID,
            SUBJECT_NAME, LEVEL from COMMENTS_VIEW where SUBJECT_ID = :id CONNECT BY PRIOR id = comment_id
            start with COMMENT_ID is null order siblings by id",
            [':id' => $id]
        );
    }

}
