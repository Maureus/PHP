<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
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
        'type',
        'created_at',
        'updated_by',
        'subject_id',
    ];

    public function quizzes() {
        return $this->belongsToMany(Quiz::class);
    }

    static public function insertQuestion($request) {
        $conn = DBC::getConnection();
        $sql = 'begin insert_or_update_question(
                           p_name => :name,
                           p_answer_1 => :answer_1,
                           p_answer_2 => :answer_2,
                           p_answer_correct => :answer_correct,
                           p_subject_id => :subject_id,
                           p_id_out => :v_id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $name = $request->name;
        $answer_1 = $request->answer_1;
        $answer_2 = $request->answer_2;
        $answer_correct = $request->answer_correct;
        $subject_id = $request->subject_id;

        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':answer_1', $answer_1, -1);
        oci_bind_by_name($stmt, ':answer_2', $answer_2, -1);
        oci_bind_by_name($stmt, ':answer_correct', $answer_correct, -1);
        oci_bind_by_name($stmt, ':subject_id', $subject_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function updateQuestion($request, $id) {
        $conn = DBC::getConnection();
        $sql = 'begin insert_or_update_question(p_id => :id,
                           p_name => :name,
                           p_answer_1 => :answer_1,
                           p_answer_2 => :answer_2,
                           p_answer_correct => :answer_correct,
                           p_subject_id => :subject_id,
                           p_id_out => :v_id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $name = $request->name;
        $answer_1 = $request->answer_1;
        $answer_2 = $request->answer_2;
        $answer_correct = $request->answer_correct;
        $subject_id = $request->subject_id;

        oci_bind_by_name($stmt, ':id', $id, -1);
        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':answer_1', $answer_1, -1);
        oci_bind_by_name($stmt, ':answer_2', $answer_2, -1);
        oci_bind_by_name($stmt, ':answer_correct', $answer_correct, -1);
        oci_bind_by_name($stmt, ':subject_id', $subject_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function deleteQuestion($id) {
        $conn = DBC::getConnection();
        $sql = 'begin delete_question(p_id => :id); end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, 255);
        oci_execute($stmt);
        oci_close($conn);
    }

    static public function selectQuestion($id) {
        return DB::selectOne("select * from QUESTIONS_VIEW where id = :id",
            [':id' => $id]);
    }

    static public function selectAllQuestions(): array {
        return DB::select("select * from QUESTIONS_VIEW order by id");
    }

    static public function selectAllSubjectQuestions($id): array {
        return DB::select(
            "select * from QUESTIONS_VIEW where subject_id = :subject_id order by id",
            [':subject_id' => $id]
        );
    }

    static public function insertQuestionInQuiz($request, $id) {
        $conn = DBC::getConnection();
        $sql = 'begin insert_question_in_quiz(:quiz_id, :name, :answer_1, :answer_2, :answer_correct, :subject_id, :v_id_out); end;';
        $stmt = oci_parse($conn, $sql);
        $name = $request->name;
        $answer_1 = $request->answer_1;
        $answer_2 = $request->answer_2;
        $answer_correct = $request->answer_correct;
        $subject_id = $request->subject_id;

        oci_bind_by_name($stmt, ':quiz_id', $id, -1);
        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':answer_1', $answer_1, -1);
        oci_bind_by_name($stmt, ':answer_2', $answer_2, -1);
        oci_bind_by_name($stmt, ':answer_correct', $answer_correct, -1);
        oci_bind_by_name($stmt, ':subject_id', $subject_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

}
