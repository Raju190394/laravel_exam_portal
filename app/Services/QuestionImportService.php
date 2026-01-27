<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Option;
use App\Models\Exam;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Image;
use Illuminate\Support\Str;

class QuestionImportService
{
    public function importFromDocx(Exam $exam, $filePath)
    {
        $phpWord = IOFactory::load($filePath);
        $questions = [];
        $currentQuestion = null;
        $currentOptions = [];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text = $this->getElementText($element);
                $imageData = $this->getElementImage($element);

                // Simple logic: If line starts with "Q:" or is a question start
                if (preg_match('/^Q\d*:?\s*(.*)/i', $text, $matches) || (is_numeric(substr(trim($text), 0, 1)) && strpos($text, '.') < 5)) {
                    // Save previous question if exists
                    if ($currentQuestion) {
                        $this->saveQuestion($exam, $currentQuestion, $currentOptions);
                    }

                    $currentQuestion = [
                        'text' => $matches[1] ?? $text,
                        'image' => $imageData
                    ];
                    $currentOptions = [];
                } elseif (preg_match('/^([A-D])[\)\.]\s*(.*)/i', $text, $matches)) {
                    $optionText = $matches[2];
                    $isCorrect = Str::contains($text, '(Correct)');
                    $optionText = str_replace('(Correct)', '', $optionText);

                    $currentOptions[] = [
                        'text' => trim($optionText),
                        'image' => $imageData,
                        'is_correct' => $isCorrect
                    ];
                } elseif ($currentQuestion && !empty(trim($text)) && empty($currentOptions)) {
                    // Append to question text if it's multiple lines
                    $currentQuestion['text'] .= "\n" . $text;
                }
            }
        }

        // Save last question
        if ($currentQuestion) {
            $this->saveQuestion($exam, $currentQuestion, $currentOptions);
        }
    }

    private function getElementText($element)
    {
        $text = '';
        if ($element instanceof Text) {
            $text = $element->getText();
        } elseif ($element instanceof TextRun) {
            foreach ($element->getElements() as $childElement) {
                if ($childElement instanceof Text) {
                    $text .= $childElement->getText();
                }
            }
        }
        return $text;
    }

    private function getElementImage($element)
    {
        if ($element instanceof Image) {
            return $element->getSource();
        }
        if ($element instanceof TextRun) {
            foreach ($element->getElements() as $childElement) {
                if ($childElement instanceof Image) {
                    return $childElement->getSource();
                }
            }
        }
        return null;
    }

    private function saveQuestion(Exam $exam, $questionData, $optionsData)
    {
        $imagePath = null;
        if ($questionData['image']) {
            $imageName = Str::random(20) . '.png';
            Storage::disk('public')->put('exams/questions/' . $imageName, file_get_contents($questionData['image']));
            $imagePath = 'exams/questions/' . $imageName;
        }

        $question = Question::create([
            'exam_id' => $exam->id,
            'question_text' => $questionData['text'],
            'question_image' => $imagePath,
            'marks' => $exam->marks_per_question ?? 1,
            'negative_marks' => $exam->negative_marking_value ?? 0,
        ]);

        foreach ($optionsData as $optionData) {
            $optImagePath = null;
            if ($optionData['image']) {
                $optImageName = Str::random(20) . '.png';
                Storage::disk('public')->put('exams/options/' . $optImageName, file_get_contents($optionData['image']));
                $optImagePath = 'exams/options/' . $optImageName;
            }

            Option::create([
                'question_id' => $question->id,
                'option_text' => $optionData['text'],
                'option_image' => $optImagePath,
                'is_correct' => $optionData['is_correct']
            ]);
        }
    }
}
