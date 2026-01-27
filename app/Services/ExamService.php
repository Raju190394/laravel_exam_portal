<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\StudentExam;
use App\Models\StudentAnswer;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamService
{
    public function startExam(Exam $exam)
    {
        return StudentExam::create([
            'user_id' => Auth::id(),
            'exam_id' => $exam->id,
            'started_at' => now(),
            'status' => 'ongoing',
            'total_questions' => $exam->questions()->count(),
        ]);
    }

    public function submitAnswer(StudentExam $studentExam, $questionId, $optionId)
    {
        $question = Question::findOrFail($questionId);
        $option = $optionId ? Option::findOrFail($optionId) : null;
        
        $isCorrect = $option ? $option->is_correct : false;
        $marksObtained = 0;

        if ($option) {
            if ($isCorrect) {
                $marksObtained = $question->marks;
            } else {
                $marksObtained = -($question->negative_marks);
            }
        }

        return StudentAnswer::updateOrCreate(
            [
                'student_exam_id' => $studentExam->id,
                'question_id' => $questionId,
            ],
            [
                'option_id' => $optionId,
                'is_correct' => $isCorrect,
                'marks_obtained' => $marksObtained,
            ]
        );
    }

    public function finalizeExam(StudentExam $studentExam)
    {
        $answers = $studentExam->answers;
        
        $totalAttempted = $answers->whereNotNull('option_id')->count();
        $totalCorrect = $answers->where('is_correct', true)->count();
        $totalWrong = $totalAttempted - $totalCorrect;
        $totalScore = $answers->sum('marks_obtained');

        $studentExam->update([
            'submitted_at' => now(),
            'status' => 'completed',
            'attempted_questions' => $totalAttempted,
            'correct_answers' => $totalCorrect,
            'wrong_answers' => $totalWrong,
            'score' => $totalScore,
        ]);

        return $studentExam;
    }

    public function checkTimeout(StudentExam $studentExam)
    {
        $exam = $studentExam->exam;
        $endTime = $studentExam->started_at->addMinutes($exam->duration_minutes);

        if (now()->greaterThan($endTime) && $studentExam->status === 'ongoing') {
            $this->finalizeExam($studentExam);
            $studentExam->update(['status' => 'timed_out']);
            return true;
        }

        return false;
    }
}
