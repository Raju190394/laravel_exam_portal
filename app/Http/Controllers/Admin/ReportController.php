<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Exports\ExamResultsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $exams = Exam::withCount(['studentExams as total_attempts' => function($query) {
            $query->where('status', 'completed');
        }])->with(['course'])->latest()->paginate(10);

        return view('pages.admin.reports.index', compact('exams'));
    }

    public function examReport(Exam $exam)
    {
        $results = StudentExam::where('exam_id', $exam->id)
            ->with('user')
            ->where('status', 'completed')
            ->orderBy('score', 'desc')
            ->get();

        return view('pages.admin.reports.exam_details', compact('exam', 'results'));
    }

    public function exportExcel(Exam $exam)
    {
        return Excel::download(new ExamResultsExport($exam->id), "{$exam->slug}_results.xlsx");
    }

    public function exportPdf(Exam $exam)
    {
        $results = StudentExam::where('exam_id', $exam->id)
            ->with('user')
            ->where('status', 'completed')
            ->orderBy('score', 'desc')
            ->get();

        $pdf = Pdf::loadView('pages.admin.reports.pdf_exam', compact('exam', 'results'));
        return $pdf->download("{$exam->slug}_report.pdf");
    }

    public function studentResultPdf(StudentExam $studentExam)
    {
        $studentExam->load(['user', 'exam', 'answers.question.options']);
        $pdf = Pdf::loadView('pages.student.exams.result_pdf', compact('studentExam'));
        return $pdf->download("result_{$studentExam->id}.pdf");
    }
}
