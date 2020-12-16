<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    const COMMENT_TABLE = 'comments';

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

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment(){
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }

    static public function selectSubCommentsTreeStartingFromId($id){
        return DB::select("select * from ".Comment::COMMENT_TABLE." START WITH id = :id".
            " CONNECT BY PRIOR id = comment_id",
            [':id'=>$id]);
    }

//p_id IN COMMENTS.id%TYPE DEFAULT NULL,
//p_text IN COMMENTS.TEXT%TYPE,
//p_comment_id IN COMMENTS.COMMENT_ID%TYPE DEFAULT NULL,
//p_user_id IN COMMENTS.USER_ID%TYPE,
//p_subject_id IN COMMENTS.SUBJECT_ID%TYPE,
//p_id_out OUT COMMENTS.id%TYPE) as

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
        $user_id = $request->user_id;
        $subject_id = $request->subject_id;

        oci_bind_by_name($stmt, ':name', $text, -1);
        oci_bind_by_name($stmt, ':semester', $comment_id, -1);
        oci_bind_by_name($stmt, ':year', $user_id, -1);
        oci_bind_by_name($stmt, ':short_name', $subject_id, -1);
        oci_bind_by_name($stmt, ':subject_desc', $category_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function updateComment($request, $id) {
        $conn = DBC::getConnection();
        $sql = 'begin insert_or_update_comment(p_id => :id
                           p_name => :name,
                           p_answer_1 => :answer_1,
                           p_answer_2 => :answer_2,
                           p_answer_correct => :answer_correct,
                           p_category_id => :category_id,
                           p_id_out => :v_id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $name = $request->name;
        $answer_1 = $request->answer_1;
        $answer_2 = $request->answer_2;
        $answer_correct = $request->answer_correct;
        $category_id = $request->category_id;

        oci_bind_by_name($stmt, ':id', $id, -1);
        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':semester', $answer_1, -1);
        oci_bind_by_name($stmt, ':year', $answer_2, -1);
        oci_bind_by_name($stmt, ':short_name', $answer_correct, -1);
        oci_bind_by_name($stmt, ':subject_desc', $category_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function deleteComment($id) {
        $conn = DBC::getConnection();
        $sql = 'begin delete_comment(p_id => :id); end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, 255);
        oci_execute($stmt);
        oci_close($conn);
    }

    static public function selectComment($id) {
        return DB::selectOne("select * from COMMENTS_VIEW where id = :id",
            [':id' => $id]);
    }

    static public function selectAllComments(): array {
        return DB::select("select * from COMMENTS_VIEW order by id");
    }

    static public function selectAllSubjectComments($id): array {
        return DB::select("select * from COMMENTS_VIEW where SUBJECT_ID = :id order by id", [':id'=>$id]);
    }

}
