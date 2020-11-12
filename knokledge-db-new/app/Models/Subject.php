<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    const SUBJECTS_TABLE = 'subjects';
    const SUBJECTS_VISIBLE = 'id, name, semester, year, short_name, subject_desc, created_at, updated_at';

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
        'semester',
        'year',
        'short_name',
        'subject_desc',
        'created_at',
        'updated_at',
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
