<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'negative_marking_value',
        'randomize_questions',
        'randomize_options',
        'one_question_at_a_time',
        'is_published',
        'created_by',
        'course_id',
        'active_from',
        'active_until',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_published', true)
                     ->where(function($q) use ($now) {
                         $q->whereNull('active_from')
                           ->orWhere('active_from', '<=', $now);
                     })
                     ->where(function($q) use ($now) {
                         $q->whereNull('active_until')
                           ->orWhere('active_until', '>=', $now);
                     });
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function studentExams()
    {
        return $this->hasMany(StudentExam::class);
    }
}
