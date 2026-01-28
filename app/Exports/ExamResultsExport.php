<?php

namespace App\Exports;

use App\Models\StudentExam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamResultsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $examId;

    public function __construct($examId)
    {
        $this->examId = $examId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return StudentExam::where('exam_id', $this->examId)
            ->with('user')
            ->orderBy('score', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Email',
            'Score',
            'Total Marks',
            'Percentage',
            'Status',
            'Submitted At'
        ];
    }

    public function map($studentExam): array
    {
        $percentage = ($studentExam->exam->total_marks > 0) 
            ? round(($studentExam->score / $studentExam->exam->total_marks) * 100, 2) 
            : 0;

        return [
            $studentExam->user->name,
            $studentExam->user->email,
            $studentExam->score,
            $studentExam->exam->total_marks,
            $percentage . '%',
            ucfirst($studentExam->status),
            $studentExam->completed_at ? $studentExam->completed_at->format('d M Y, H:i') : 'N/A'
        ];
    }
}
