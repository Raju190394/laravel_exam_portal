<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $courseIds;

    public function __construct(array $courseIds)
    {
        $this->courseIds = $courseIds;
    }

    public function model(array $row)
    {
        if (!isset($row['name']) || !isset($row['email'])) {
            return null;
        }

        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['name'],
                'password' => Hash::make($row['password'] ?? 'password123'),
                'role' => 'student',
            ]
        );

        // Enroll in selected courses
        if (!empty($this->courseIds)) {
            $user->courses()->syncWithoutDetaching($this->courseIds);
        }

        return $user;
    }
}
