<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Result Overview -->
        <div class="mb-8 bg-white dark:bg-gray-800 shadow-lg rounded-3xl overflow-hidden">
            <div class="bg-indigo-600 p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h1 class="text-3xl font-extrabold mb-2">{{ $studentExam->exam->title }} - Results</h1>
                    <p class="opacity-80">Submitted on {{ $studentExam->submitted_at->format('M d, Y h:i A') }}</p>
                </div>
                <!-- Abstract Design Decor -->
                <div class="absolute -right-12 -top-12 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-indigo-900/40 rounded-full blur-2xl"></div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Score Card -->
                <div class="text-center p-6 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl border-2 border-indigo-100 dark:border-indigo-500/20">
                    <div class="text-xs text-indigo-500 uppercase font-bold tracking-widest mb-1">Total Score</div>
                    <div class="text-4xl font-black text-indigo-700 dark:text-indigo-400">{{ $studentExam->score }}</div>
                    <div class="text-sm text-gray-500 mt-1">out of {{ $studentExam->exam->total_marks }}</div>
                </div>

                <!-- Stats -->
                <div class="text-center p-6 bg-green-50 dark:bg-green-500/10 rounded-2xl">
                    <div class="text-xs text-green-600 uppercase font-bold mb-1">Correct</div>
                    <div class="text-3xl font-bold text-green-600">{{ $studentExam->correct_answers }}</div>
                </div>

                <div class="text-center p-6 bg-red-50 dark:bg-red-500/10 rounded-2xl">
                    <div class="text-xs text-red-600 uppercase font-bold mb-1">Incorrect</div>
                    <div class="text-3xl font-bold text-red-600">{{ $studentExam->wrong_answers }}</div>
                </div>

                <div class="text-center p-6 bg-gray-50 dark:bg-gray-700/30 rounded-2xl">
                    <div class="text-xs text-gray-500 uppercase font-bold mb-1">Attempted</div>
                    <div class="text-3xl font-bold text-gray-700 dark:text-gray-300">{{ $studentExam->attempted_questions }} / {{ $studentExam->total_questions }}</div>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-gray-100">Review Questions</h2>

        <div class="space-y-6">
            @foreach($studentExam->answers as $index => $answer)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border-l-4 {{ $answer->is_correct ? 'border-green-500' : ($answer->option_id ? 'border-red-500' : 'border-gray-300') }}">
                <div class="flex items-start justify-between mb-4">
                    <h3 class="font-bold text-lg">Q{{ $index + 1 }}: {{ $answer->question->question_text }}</h3>
                    <div class="text-right">
                        <span class="text-xs font-bold uppercase px-3 py-1 rounded-full {{ $answer->is_correct ? 'bg-green-100 text-green-700' : ($answer->option_id ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ $answer->is_correct ? 'Correct (+' . $answer->marks_obtained . ')' : ($answer->option_id ? 'Incorrect (' . $answer->marks_obtained . ')' : 'Not Attempted') }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($answer->question->options as $option)
                    @php
                        $isUserChoice = $answer->option_id == $option->id;
                        $isCorrect = $option->is_correct;
                    @endphp
                    <div class="p-4 rounded-xl border-2 transition-all {{ $isCorrect ? 'border-green-500 bg-green-50/50' : ($isUserChoice ? 'border-red-500 bg-red-50/50' : 'border-gray-100') }}">
                        <div class="flex items-center">
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg mr-3 {{ $isCorrect ? 'bg-green-500 text-white' : ($isUserChoice ? 'bg-red-500 text-white' : 'bg-gray-100') }}">
                                {{ chr(65 + $loop->index) }}
                            </span>
                            <span class="font-medium {{ $isCorrect ? 'text-green-700' : ($isUserChoice ? 'text-red-700' : '') }}">{{ $option->option_text }}</span>
                            @if($isCorrect)
                                <svg class="w-5 h-5 ml-auto text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            @elseif($isUserChoice)
                                <svg class="w-5 h-5 ml-auto text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center flex items-center justify-center space-x-4">
            <a href="{{ route('student.exams.index') }}" class="btn bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-2xl shadow-lg transition-all transform hover:scale-105">
                Back to Dashboard
            </a>
            <a href="{{ route('student.results.pdf', $studentExam) }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white px-8 py-3 rounded-2xl shadow-lg transition-all transform hover:scale-105 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </a>
        </div>

    </div>
</x-app-layout>
