<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Quiz extends Model
{
    const QUIZ_TABLE = 'quizzes';
    const QUIZ_QUESTIONS_VIEW = 'quiz_questions_view';
    const QUIZ_CAT_QUESTIONS_VIEW = 'quiz_cat_questions_view';

    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'date_from',
        'date_till',
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
        'date_from',
        'date_till',
        'quiz_desc',
        'num_questions',
        'points_fq',
        'created_at',
        'updated_by',
        'subject_id',
    ];

    public function questions() {
        return $this->belongsToMany(Question::class);
    }

    public function setQuestion($question) {
        $this->questions()->save($question);
    }

    static public function selectAllQuizQuestions($id): array {
        return DB::select(
            "select * from " . Quiz::QUIZ_QUESTIONS_VIEW . " where quiz_id = :quiz_id order by id",
            [':quiz_id' => $id]
        );
    }

    static public function selectQuizQuestionsByCategory($id): array {
        return DB::select(
            "select * from " . Quiz::QUIZ_CAT_QUESTIONS_VIEW . " where quiz_id = :quiz_id order by id",
            [':quiz_id' => $id]
        );
    }

    static public function selectAll(): array {
        return DB::select("select * from quizzes_view order by id");
    }

    static public function selectById($id) {
        return DB::selectOne("select * from quizzes_view where id = :id",
            [':id' => $id]);
    }

    static public function insertQuiz($request) {
        $conn = DBC::getConnection();
        $sql = 'begin insert_or_update_quiz(
                           p_name => :name,
                           p_type => :type,
                           p_date_from => :date_from,
                           p_date_till => :date_till,
                           p_quiz_desc => :quiz_desc,
                           p_num_question => :num_questions,
                           p_points_fq => :points_fq,
                           p_subject_id => :subject_id,
                           p_user_id => :user_id,
                           p_id_out => :v_id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $name = $request->name;
        $type = $request->type;
        $date_from = $request->date_from;
        $date_till = $request->date_till;
        $quiz_desc = $request->quiz_desc;
        $num_questions = $request->num_questions;
        $points_fq = $request->points_fq;
        $subject_id = $request->subject_id;
        $user_id = Auth::id();

        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':type', $type, -1);
        oci_bind_by_name($stmt, ':date_from', $date_from, -1);
        oci_bind_by_name($stmt, ':date_till', $date_till, -1);
        oci_bind_by_name($stmt, ':quiz_desc', $quiz_desc, -1);
        oci_bind_by_name($stmt, ':num_questions', $num_questions, -1);
        oci_bind_by_name($stmt, ':points_fq', $points_fq, -1);
        oci_bind_by_name($stmt, ':subject_id', $subject_id, -1);
        oci_bind_by_name($stmt, ':user_id', $user_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function updateQuiz($request, $id): int {
        $conn = DBC::getConnection();
        $sql = 'begin insert_or_update_quiz(p_id => :id,
                           p_name => :name,
                           p_type => :type,
                           p_date_from => :date_from,
                           p_date_till => :date_till,
                           p_quiz_desc => :quiz_desc,
                           p_num_question => :num_questions,
                           p_points_fq => :points_fq,
                           p_id_out => :v_id_out);
                        end;';
        $stmt = oci_parse($conn, $sql);
        $name = $request->name;
        $type = $request->type;
        $date_from = $request->date_from;
        $date_till = $request->date_till;
        $quiz_desc = $request->quiz_desc;
        $num_questions = $request->num_questions;
        $points_fq = $request->points_fq;


        oci_bind_by_name($stmt, ':id', $id, -1);
        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':type', $type, -1);
        oci_bind_by_name($stmt, ':date_from', $date_from, -1);
        oci_bind_by_name($stmt, ':date_till', $date_till, -1);
        oci_bind_by_name($stmt, ':quiz_desc', $quiz_desc, -1);
        oci_bind_by_name($stmt, ':num_questions', $num_questions, -1);
        oci_bind_by_name($stmt, ':points_fq', $points_fq, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
    }

    static public function deleteQuiz($id) {
        $conn = DBC::getConnection();
        $sql = 'begin delete_quiz(p_id => :id); end;';
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id, 255);
        oci_execute($stmt);
        oci_close($conn);
    }

    static public function selectSubjectQuizzes($id): array {
        return DB::select("select * from QUIZZES_VIEW where SUBJECT_ID = :id order by ID",
            [':id' => $id]);
    }

    // expects array of ids
//    static public function insertAllQuestionToQuiz(Request $request, $id) {
//        $seize = $request->seize;
//        $questions = $request->questions;
//        $questionOne = $request->questions[0];
//        return $request->questions;
//    }

    static public function selectQuizResults($id): array {
        return DB::select('select * from QUIZ_USER_RESULT_VIEW where QUIZ_ID = :id', [':id'=>$id]);
    }

    static public function selectUserResults($id): array {
        return DB::select('select * from QUIZ_USER_RESULT_VIEW where USER_ID = :id', [':id'=>$id]);
    }

    static public function insertQuizResult($quiz_id, $result): bool {
        return DB::insert(
            'insert into QUIZ_USER_RESULT(quiz_id, user_id, result) VALUES (:quiz_id, :user_id, :result)',
            [':quiz_id'=>$quiz_id, ':user_id'=>Auth::id(), ':result'=>$result]
        );
    }

    static public function assignQuizQuestion($quizId, $questionId): bool {
        return DB::insert(
            'insert into QUESTION_QUIZ values(:quizId, :questionId)',
            [':quizId'=>$quizId, ':questionId'=>$questionId]
        );
    }

    static public function removeQuizQuestion($quizId, $questionId): int {
        return DB::delete(
            'insert into QUESTION_QUIZ values(:quizId, :questionId)',
            [':quizId'=>$quizId, ':questionId'=>$questionId]
        );
    }
}
