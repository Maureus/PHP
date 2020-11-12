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


}
