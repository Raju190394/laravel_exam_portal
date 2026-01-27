<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\QuestionImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    protected $importService;

    public function __construct(QuestionImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        $exams = Exam::with(['course'])->withCount('questions')->latest()->paginate(10);
        return view('pages.admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $courses = \App\Models\Course::where('is_active', true)->get();
        return view('pages.admin.exams.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'duration_minutes' => 'required|integer|min:1',
            'active_from' => 'nullable|date',
            'active_until' => 'nullable|date|after_or_equal:active_from',
            'negative_marking_value' => 'nullable|numeric|min:0',
            'passing_marks' => 'required|numeric|min:0',
            'randomize_questions' => 'nullable|boolean',
            'randomize_options' => 'nullable|boolean',
            'one_question_at_a_time' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $validated['created_by'] = Auth::id();
        $validated['randomize_questions'] = $request->has('randomize_questions');
        $validated['randomize_options'] = $request->has('randomize_options');
        $validated['one_question_at_a_time'] = $request->has('one_question_at_a_time');
        $validated['is_published'] = true; // Auto publish for now

        Exam::create($validated);

        return redirect()->route('admin.exams.index')->with('success', 'Exam created successfully.');
    }

    public function import(Request $request, Exam $exam)
    {
        $request->validate([
            'file' => 'required|mimes:docx|max:10240',
        ]);

        $this->importService->importFromDocx($exam, $request->file('file')->getRealPath());

        return back()->with('success', 'Questions imported successfully.');
    }

    public function show(Exam $exam)
    {
        $exam->load('questions.options');
        return view('pages.admin.exams.show', compact('exam'));
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted successfully.');
    }
}
