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

    public function questions(){
        return $this->belongsToMany(Question::class);
    }

    public function setQuestion($question){
        $this->questions()->save($question);
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }

    static public function selectAllQuizQuestions($id){
        return DB::select("select * from ".Quiz::QUIZ_QUESTIONS_VIEW." where quiz_id = :quiz_id order by id",
            [':quiz_id'=>$id]);
    }

    static public function selectQuizQuestionsByCategory($id){
        return DB::select("select * from ".Quiz::QUIZ_CAT_QUESTIONS_VIEW." where quiz_id = :quiz_id order by id",
            [':quiz_id'=>$id]);
    }
}
