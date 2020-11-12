<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'category_id',
    ];

    public function quizzes(){
        return $this->belongsToMany(Quiz::class);
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
}
