<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'is_active'];

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
