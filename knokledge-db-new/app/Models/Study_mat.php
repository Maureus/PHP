<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }
}
