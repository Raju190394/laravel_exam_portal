<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="examHandler()">

        <!-- Header with Timer -->
        <div class="fixed top-20 right-8 z-50">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-4 border-2 border-indigo-500">
                <div class="text-xs text-gray-500 uppercase font-bold text-center mb-1">Time Remaining</div>
                <div class="text-2xl font-mono font-bold text-indigo-600 dark:text-indigo-400 text-center" id="timer">
                    00:00:00
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold uppercase tracking-tight">{{ $exam->title }}</h1>
            <div class="flex items-center mt-2 text-sm text-gray-500">
                <span class="mr-4">Questions: {{ $questions->count() }}</span>
                <span>Total Marks: {{ $exam->total_marks }}</span>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <!-- Questions Section -->
            <div class="col-span-12 lg:col-span-9">
                @foreach($questions as $index => $question)
                <div class="question-card bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8 mb-6 transition-all duration-300" 
                     x-show="currentQuestion === {{ $index }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-12"
                     x-transition:enter-end="opacity-100 transform translate-x-0">
                    
                    <div class="flex items-center justify-between mb-6">
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">Question {{ $index + 1 }} of {{ $questions->count() }}</span>
                        <span class="text-sm font-medium text-gray-400">{{ $question->marks }} Marks</span>
                    </div>

                    <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-gray-100 leading-relaxed">{{ $question->question_text }}</h2>

                    @if($question->question_image)
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                        <img src="{{ Storage::url($question->question_image) }}" alt="Question Image" class="max-w-full rounded-lg shadow-sm" />
                    </div>
                    @endif

                    <div class="space-y-4">
                        @foreach($question->options as $option)
                        <label class="block cursor-pointer group">
                            <input type="radio" 
                                   name="q{{ $question->id }}" 
                                   value="{{ $option->id }}" 
                                   class="sr-only" 
                                   @change="submitAnswer({{ $question->id }}, {{ $option->id }})"
                                   :checked="answers[{{ $question->id }}] == {{ $option->id }}">
                            <div class="p-5 border-2 rounded-2xl flex items-center transition-all duration-200 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/30"
                                 :class="answers[{{ $question->id }}] == {{ $option->id }} ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-500/10' : 'border-gray-100 dark:border-gray-700'">
                                <span class="w-10 h-10 flex items-center justify-center rounded-xl mr-4 transition-colors"
                                      :class="answers[{{ $question->id }}] == {{ $option->id }} ? 'bg-indigo-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500'">
                                    {{ chr(65 + $loop->index) }}
                                </span>
                                <div class="flex-1">
                                    <span class="text-md font-medium text-gray-700 dark:text-gray-300 uppercase">{{ $option->option_text }}</span>
                                    @if($option->option_image)
                                    <img src="{{ Storage::url($option->option_image) }}" alt="Option Image" class="mt-3 max-w-xs rounded-lg shadow-xs" />
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Navigation -->
                <div class="flex justify-between items-center py-4">
                    <button class="btn border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400"
                            @click="prevQuestion()" :disabled="currentQuestion === 0">
                        Previous
                    </button>
                    
                    <div class="space-x-2">
                        <button class="btn bg-red-500 hover:bg-red-600 text-white" @click="confirmSubmit()">
                            Submit Exam
                        </button>
                        <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white"
                                @click="nextQuestion()" x-show="currentQuestion < {{ $questions->count() - 1 }}">
                            Next Question
                        </button>
                    </div>
                </div>
            </div>

            <!-- Question Palette -->
            <div class="col-span-12 lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 sticky top-24">
                    <h3 class="font-bold mb-4 text-gray-800 dark:text-gray-100">Question Palette</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-4 gap-2">
                        @foreach($questions as $index => $question)
                        <button @click="currentQuestion = {{ $index }}"
                                class="w-full aspect-square flex items-center justify-center rounded-lg text-xs font-bold transition-all duration-200"
                                :class="currentQuestion === {{ $index }} ? 'ring-2 ring-indigo-500 ring-offset-2' : (answers[{{ $question->id }}] ? 'bg-green-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-600')">
                            {{ $index + 1 }}
                        </button>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center text-xs">
                            <span class="w-3 h-3 bg-green-500 rounded mr-2"></span>
                            <span class="text-gray-500">Attempted</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <span class="w-3 h-3 bg-gray-100 dark:bg-gray-700 rounded mr-2"></span>
                            <span class="text-gray-500">Unattempted</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Submission Form -->
        <form id="submit-exam-form" action="{{ route('student.exams.complete', $studentExam) }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    @push('scripts')
    <script>
        function examHandler() {
            return {
                currentQuestion: 0,
                answers: {},
                examDurationSec: {{ $exam->duration_minutes * 60 }},
                timeSpent: 0,
                
                init() {
                    // Initialize answers from existing ones if needed
                    @foreach($studentExam->answers as $ans)
                        this.answers[{{ $ans->question_id }}] = {{ $ans->option_id }};
                    @endforeach
                    
                    // Calc initial time spent
                    const startedAt = new Date("{{ $studentExam->started_at }}").getTime();
                    const now = new Date().getTime();
                    this.timeSpent = Math.floor((now - startedAt) / 1000);

                    this.startTimer();
                },

                startTimer() {
                    const timerId = setInterval(() => {
                        this.timeSpent++;
                        const remaining = this.examDurationSec - this.timeSpent;
                        
                        if (remaining <= 0) {
                            clearInterval(timerId);
                            document.getElementById('timer').innerText = "00:00:00";
                            alert("Time is up! Your exam will be submitted automatically.");
                            this.autoSubmit();
                            return;
                        }

                        const h = Math.floor(remaining / 3600).toString().padStart(2, '0');
                        const m = Math.floor((remaining % 3600) / 60).toString().padStart(2, '0');
                        const s = (remaining % 60).toString().padStart(2, '0');
                        document.getElementById('timer').innerText = `${h}:${m}:${s}`;
                    }, 1000);
                },

                nextQuestion() {
                    if (this.currentQuestion < {{ $questions->count() - 1 }}) this.currentQuestion++;
                },

                prevQuestion() {
                    if (this.currentQuestion > 0) this.currentQuestion--;
                },

                submitAnswer(qId, oId) {
                    this.answers[qId] = oId;
                    
                    fetch("{{ route('student.exams.submit_answer', $studentExam) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            question_id: qId,
                            option_id: oId
                        })
                    });
                },

                confirmSubmit() {
                    if (confirm("Are you sure you want to submit the exam?")) {
                        document.getElementById('submit-exam-form').submit();
                    }
                },

                autoSubmit() {
                    document.getElementById('submit-exam-form').submit();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
