<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
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
        'acc_key',
        'course_desc',
        'created_by',
        'edited_by',
        'created_at',
        'updated_by',
        'subject_id',
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
