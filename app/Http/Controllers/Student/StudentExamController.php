<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Services\ExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index()
    {
        $user = Auth::user();
        $courseIds = $user->courses()->pluck('courses.id');
        
        $exams = Exam::whereIn('course_id', $courseIds)
            ->active()
            ->latest()
            ->get();
            
        $attempts = StudentExam::where('user_id', $user->id)->latest()->get();
        return view('pages.student.exams.index', compact('exams', 'attempts'));
    }

    public function logViolation(Request $request, StudentExam $studentExam)
    {
        if ($studentExam->status !== 'ongoing') return response()->json(['success' => false]);

        $studentExam->increment('tab_switch_count');
        $logs = $studentExam->violation_logs ? json_decode($studentExam->violation_logs, true) : [];
        $logs[] = [
            'type' => $request->type ?? 'tab_switch',
            'time' => now()->toDateTimeString(),
            'message' => $request->message ?? 'Student switched tab/window'
        ];
        $studentExam->violation_logs = json_encode($logs);
        $studentExam->save();

        return response()->json([
            'success' => true,
            'count' => $studentExam->tab_switch_count
        ]);
    }

    public function show(Exam $exam)
    {
        $user = Auth::user();
        // Security check: Student must be enrolled in the course
        if (!$user->courses()->where('courses.id', $exam->course_id)->exists()) {
            abort(403, 'You are not enrolled in the course for this exam.');
        }

        // Check if exam is active now
        if (!$exam->is_published || 
            ($exam->active_from && $exam->active_from > now()) || 
            ($exam->active_until && $exam->active_until < now())) {
            return redirect()->route('student.exams.index')->with('error', 'This exam is not available right now.');
        }

        // Check if there's an ongoing attempt
        $studentExam = StudentExam::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->where('status', 'ongoing')
            ->first();

        if (!$studentExam) {
            $studentExam = $this->examService->startExam($exam);
        }

        // Check for timeout
        if ($this->examService->checkTimeout($studentExam)) {
            return redirect()->route('student.results.show', $studentExam)->with('error', 'Exam timed out.');
        }

        $questions = $exam->questions();
        if ($exam->randomize_questions) {
            $questions->inRandomOrder();
        }
        $questions = $questions->with('options')->get();

        if ($exam->randomize_options) {
            foreach ($questions as $q) {
                $q->setRelation('options', $q->options->shuffle());
            }
        }

        return view('pages.student.exams.take', compact('exam', 'studentExam', 'questions'));
    }

    public function submitAnswer(Request $request, StudentExam $studentExam)
    {
        if ($studentExam->status !== 'ongoing') {
            return response()->json(['error' => 'Exam is already submitted or timed out.'], 403);
        }

        $this->examService->submitAnswer($studentExam, $request->question_id, $request->option_id);

        return response()->json(['success' => true]);
    }

    public function complete(StudentExam $studentExam)
    {
        if ($studentExam->status === 'ongoing') {
            $this->examService->finalizeExam($studentExam);
        }
        return redirect()->route('student.results.show', $studentExam);
    }

    public function result(StudentExam $studentExam)
    {
        if ($studentExam->user_id !== Auth::id()) {
            abort(403);
        }
        $studentExam->load('exam', 'answers.question.options');
        return view('pages.student.exams.result', compact('studentExam'));
    }
}
