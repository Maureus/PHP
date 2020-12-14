<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'course_id',
        'category_id',
    ];

    public function questions() {
        return $this->belongsToMany(Question::class);
    }

    public function setQuestion($question) {
        $this->questions()->save($question);
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    static public function selectAllQuizQuestions($id) {
        return DB::select("select * from " . Quiz::QUIZ_QUESTIONS_VIEW . " where quiz_id = :quiz_id order by id",
            [':quiz_id' => $id]);
    }

    static public function selectQuizQuestionsByCategory($id) {
        return DB::select("select * from " . Quiz::QUIZ_CAT_QUESTIONS_VIEW . " where quiz_id = :quiz_id order by id",
            [':quiz_id' => $id]);
    }

    static public function selectAll(): array {
        return DB::select("select * from quizzes_view order by id");
    }

    static public function selectById($id) {
        return DB::selectOne("select * from quizzes_view where id = :id",
            [':id' => $id]);
    }

//p_id IN QUIZZES.id%TYPE DEFAULT NULL,
//p_name IN QUIZZES.name%TYPE,
//p_type IN QUIZZES.type%TYPE,
//p_date_from IN QUIZZES.DATE_FROM%TYPE,
//p_date_till IN QUIZZES.DATE_TILL%TYPE,
//p_quiz_desc IN QUIZZES.QUIZ_DESC%TYPE,
//p_num_question IN QUIZZES.NUM_QUESTIONS%TYPE,
//p_points_fq IN QUIZZES.POINTS_FQ%TYPE,
//p_subject_id IN QUIZZES.SUBJECT_ID%TYPE,
//p_category_id IN QUIZZES.CATEGORY_ID%TYPE,
//p_id_out OUT QUIZZES.id%TYPE) as

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
                           p_category_id => :category_id,
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
        $category_id = $request->category_id;

        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':type', $type, -1);
        oci_bind_by_name($stmt, ':date_from', $date_from, -1);
        oci_bind_by_name($stmt, ':date_till', $date_till, -1);
        oci_bind_by_name($stmt, ':quiz_desc', $quiz_desc, -1);
        oci_bind_by_name($stmt, ':num_questions', $num_questions, -1);
        oci_bind_by_name($stmt, ':points_fq', $points_fq, -1);
        oci_bind_by_name($stmt, ':subject_id', $subject_id, -1);
        oci_bind_by_name($stmt, ':category_id', $category_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
//        return DB::insert(
//            "insert into quizzes (
//                     name,
//                     type,
//                     date_from,
//                     date_till,
//                     QUIZ_DESC,
//                     NUM_QUESTIONS,
//                     POINTS_FQ,
//                     CREATED_AT,
//                     SUBJECT_ID,
//                     CATEGORY_ID
//                     )
//                      values (
//                              :name,
//                              :type,
//                              :date_from,
//                              :date_till,
//                              :quiz_desc,
//                              :num_questions,
//                              :points_fq,
//                              Current_Timestamp(6),
//                              :subject_id,
//                              :category_id
//                              )",
//            [
//                ':name' => $request->name,
//                ':type' => $request->type,
//                ':date_from' => $request->date_from,
//                ':date_till' => $request->date_till,
//                ':quiz_desc' => $request->quiz_desc,
//                ':num_questions' => $request->num_questions,
//                ':points_fq' => $request->points_fq,
//                ':subject_id' => $request->subject_id,
//                ':category_id' => $request->category_id
//            ]);
    }

    static public function updateQuiz($request, $id): int {
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
                           p_category_id => :category_id,
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
        $category_id = $request->category_id;

        oci_bind_by_name($stmt, ':name', $name, -1);
        oci_bind_by_name($stmt, ':type', $type, -1);
        oci_bind_by_name($stmt, ':date_from', $date_from, -1);
        oci_bind_by_name($stmt, ':date_till', $date_till, -1);
        oci_bind_by_name($stmt, ':quiz_desc', $quiz_desc, -1);
        oci_bind_by_name($stmt, ':num_questions', $num_questions, -1);
        oci_bind_by_name($stmt, ':points_fq', $points_fq, -1);
        oci_bind_by_name($stmt, ':subject_id', $subject_id, -1);
        oci_bind_by_name($stmt, ':category_id', $category_id, -1);
        oci_bind_by_name($stmt, ':v_id_out', $idOut, 255);
        oci_execute($stmt);
        oci_close($conn);

        return $idOut;
//        return DB::update(
//            'update quizzes set
//                   name = :name,
//                   type = :type,
//                   date_from = :date_from,
//                   date_till = :date_till,
//                   quiz_desc = :quiz_desc,
//                   num_questions = :num_questions,
//                   points_fq = :points_fq,
//                   UPDATED_AT = CURRENT_TIMESTAMP(6),
//                   subject_id = :subject_id,
//                   category_id = :category_id
//            where id = :id',
//            [
//                ':id' => $id,
//                ':name' => $request->name,
//                ':type' => $request->type,
//                ':date_from' => $request->date_from,
//                ':date_till' => $request->date_till,
//                ':quiz_desc' => $request->quiz_desc,
//                ':num_questions' => $request->num_questions,
//                ':points_fq' => $request->points_fq,
//                ':subject_id' => $request->subject_id,
//                ':category_id' => $request->category_id,
//            ]
//        );
    }

    static public function deleteQuiz($id): int {
        return DB::delete('delete from quizzes where id = :id', [':id' => $id]);
    }

    static public function selectSubjectQuizzes($id): array {
        return DB::select("select * from QUIZZES_VIEW where SUBJECT_ID = :id order by ID",
            [':id' => $id]);
    }
}
