<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_EXAMINER = 'examiner';
    const ROLE_STUDENT = 'student';

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isExaminer()
    {
        return $this->role === self::ROLE_EXAMINER;
    }

    public function isStudent()
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function examsCreated()
    {
        return $this->hasMany(Exam::class, 'created_by');
    }

    public function studentExams()
    {
        return $this->hasMany(StudentExam::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user');
    }
}
