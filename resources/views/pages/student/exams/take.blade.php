<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $exam->title }} - Exam Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar for the palette and question area */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1; 
            border-radius: 3px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8; 
        }

        /* Exam specific styles */
        body { overflow: hidden; } /* Prevent main body scroll */
        
        .exam-header { 
            height: 60px;
            background: #1e293b; /* Slate 800 */
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 50;
        }

        .exam-container {
            display: flex;
            height: calc(100vh - 60px); /* Full height minus header */
            background: #f3f4f6; /* Gray 100 */
        }

        .question-section {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
            display: flex;
            flex-direction: column;
        }

        .palette-section {
            width: 320px;
            background: white;
            border-left: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 40;
        }

        /* Option Styling */
        .option-card {
            display: flex;
            border: 1px solid #e5e7eb;
            background: white;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .option-card:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
        }
        .option-key {
            background: #e5e7eb;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #374151;
            flex-shrink: 0;
        }
        .option-card input:checked ~ .option-key {
            background: #3b82f6; /* Blue 500 */
            color: white;
        }
        .option-card input:checked ~ .option-content {
            background: #eff6ff; /* Blue 50 */
            border-color: #3b82f6;
        }
        .option-content {
            padding: 15px;
            flex: 1;
            font-size: 1rem;
            color: #1f2937;
        }

        /* Palette Grid Buttons */
        .palette-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* 5 cols is standard */
            gap: 8px;
            padding: 15px;
            overflow-y: auto;
            align-content: start; /* Prevent stretching */
        }
        .p-btn {
            width: 100%;
            aspect-ratio: 1 / 1; /* Force square */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #4b5563;
            cursor: pointer; /* Ensure pointer cursor */
        }
        .p-btn:hover { background: #e5e7eb; }
        
        .p-btn.current { background: #ef4444; color: white; border-color: #ef4444; } /* Red */
        .p-btn.answered { background: #22c55e; color: white; border-color: #22c55e; } /* Green */
        .p-btn.marked { background: #a855f7; color: white; border-color: #a855f7; } /* Purple */
        .p-btn.visited { background: #9ca3af; color: white; border-color: #9ca3af; } /* Gray */

        /* Legend Dots */
        .legend-dot { width: 12px; height: 12px; display: inline-block; border-radius: 2px; margin-right: 6px; }
        .l-success { background: #22c55e; }
        .l-danger { background: #ef4444; }
        .l-purple { background: #a855f7; }
        .l-gray { background: #9ca3af; }
        .l-default { background: #f3f4f6; border: 1px solid #d1d5db; }
    </style>
</head>
<body class="font-sans antialiased" x-data="examHandler()">

    <!-- Fixed Header -->
    <header class="exam-header">
        <div class="flex items-center gap-4">
            <div class="bg-blue-600 w-10 h-10 rounded flex items-center justify-center font-bold text-xl shadow-lg">
                {{ substr($exam->title, 0, 1) }}
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">{{ $exam->title }}</h1>
                <div class="text-xs text-gray-300">{{ $exam->course->title ?? 'Course Exam' }}</div>
            </div>
        </div>

        <div class="flex flex-col items-center">
            <div class="text-xs uppercase tracking-widest text-gray-400 mb-0.5">Time Remaining</div>
            <div class="font-mono text-2xl font-bold tracking-wider text-green-400" id="timer">00:00:00</div>
        </div>

        <button @click="confirmSubmit()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow transition font-medium text-sm">
            Finish Exam
        </button>
    </header>

    <!-- Main Exam Container -->
    <div class="exam-container">
        
        <!-- Left: Question Area -->
        <div class="question-section custom-scroll relative flex flex-col h-full p-0">
            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8 custom-scroll">
                @if($questions->count() > 0)
                    @foreach($questions as $index => $question)
                    <div x-show="currentQuestion === {{ $index }}" class="max-w-4xl mx-auto w-full bg-white rounded-lg shadow-sm border border-gray-200 p-8 min-h-[400px]">
                        
                        <!-- Question Header -->
                        <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                            <div>
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded">Question {{ $index + 1 }}</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-500">
                                Max Marks: {{ $question->marks }}
                                @if($question->negative_marks > 0)
                                    <span class="text-red-400 text-xs ml-1">(-{{ $question->negative_marks }} neg.)</span>
                                @endif
                            </div>
                        </div>

                        <!-- Question Text -->
                        <div class="prose max-w-none mb-8">
                            <h3 class="text-xl font-medium text-gray-800 leading-relaxed">{{ $question->question_text }}</h3>
                            
                            @if($question->question_image)
                            <div class="mt-4 p-2 border rounded bg-gray-50 inline-block">
                                <img src="{{ asset('storage/'.$question->question_image) }}" alt="Question Image" class="max-h-96 rounded" />
                            </div>
                            @endif
                        </div>

                        <!-- Options -->
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">Select Answer</h4>
                            <div class="space-y-0">
                                @foreach($question->options as $optIndex => $option)
                                <label class="option-card group">
                                    <input type="radio" 
                                           name="q{{ $question->id }}" 
                                           value="{{ $option->id }}" 
                                           class="sr-only" 
                                           @change="submitAnswer({{ $question->id }}, {{ $option->id }})"
                                           :checked="answers[{{ $question->id }}] == {{ $option->id }}">
                                    <div class="option-key group-hover:bg-gray-300 transition-colors">
                                        {{ chr(65 + $optIndex) }}
                                    </div>
                                    <div class="option-content">
                                        <div class="font-medium">{{ $option->option_text }}</div>
                                        @if($option->option_image)
                                        <img src="{{ asset('storage/'.$option->option_image) }}" alt="Option" class="mt-2 h-16 border rounded bg-white p-1" />
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    @endforeach
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center h-full">
                        <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 max-w-md">
                            <h3 class="text-lg font-medium text-gray-900">No Questions Found</h3>
                            <p class="text-gray-500 mt-2">This exam currently has no questions assigned to it.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Fixed Footer Action Bar -->
            <div class="bg-white border-t border-gray-200 p-4 shadow-[0_-2px_10px_rgba(0,0,0,0.05)] z-10 flex justify-between items-center">
                <button type="button" @click="toggleMark()" 
                        class="px-4 py-2 rounded border transition text-sm font-medium flex items-center gap-2"
                        :class="isMarked(currentQuestion) ? 'bg-purple-100 border-purple-300 text-purple-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <span x-text="isMarked(currentQuestion) ? 'Unmark Review' : 'Mark for Review'"></span>
                </button>

                <div class="flex gap-3">
                    <button type="button" @click="prevQuestion()" 
                            class="px-5 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition"
                            :disabled="currentQuestion === 0">
                        Previous
                    </button>
                    
                    <button type="button" @click="nextQuestion()" 
                            class="px-5 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition"
                            x-show="currentQuestion < questionsCount - 1">
                        <span>Next & Save</span>
                    </button>
                    
                    <button type="button" @click="confirmSubmit()" 
                            class="px-5 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition"
                            x-show="currentQuestion === questionsCount - 1" style="display: none;" x-effect="$el.style.display = (currentQuestion === questionsCount - 1) ? 'flex' : 'none'">
                        <span>Submit Exam</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: Palette Section -->
        <div class="palette-section shadow-lg">
            <!-- User Info (Small) -->
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold overflow-hidden">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/'.Auth::user()->photo) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr(Auth::user()->name, 0, 1) }}
                    @endif
                </div>
                <div class="overflow-hidden">
                    <div class="font-bold text-sm text-gray-800 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 truncate">Candidate ID: {{ Auth::id() }}</div>
                </div>
            </div>

            <!-- Palette Grid -->
            <div class="flex-1 flex flex-col min-h-0">
                <div class="p-3 border-b border-gray-100 bg-white">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Question Palette</h3>
                </div>
                
                <div class="palette-grid custom-scroll flex-1">
                    @foreach($questions as $index => $question)
                    <button @click="jumpToQuestion({{ $index }})"
                            class="p-btn transition-colors duration-150"
                            :class="getPaletteClass({{ $index }}, {{ $question->id }})">
                        {{ $index + 1 }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Compact Legend -->
            <div class="p-4 bg-gray-50 border-t border-gray-200 text-xs">
                <div class="grid grid-cols-2 gap-y-2 gap-x-1">
                    <div class="flex items-center"><span class="legend-dot l-success"></span> Answered (<span x-text="answeredCount"></span>)</div>
                    <div class="flex items-center"><span class="legend-dot l-danger"></span> Not Answered (<span x-text="notAnsweredCount"></span>)</div>
                    <div class="flex items-center"><span class="legend-dot l-purple"></span> Marked (<span x-text="markedCount"></span>)</div>
                    <div class="flex items-center"><span class="legend-dot l-gray"></span> Not Visited (<span x-text="notVisitedCount"></span>)</div>
                </div>
            </div>
            
            <div class="p-3 border-t border-gray-200 text-center bg-white">
                <a href="#" class="text-xs text-blue-500 hover:underline">Instructions</a>
            </div>
        </div>

    </div>

    <!-- Hidden Submission Form -->
    <form id="submit-exam-form" action="{{ route('student.exams.complete', $studentExam) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('examHandler', () => ({
                currentQuestion: 0,
                questionsCount: {{ $questions->count() }},
                answers: {},
                visited: new Set([0]),
                marked: new Set(),
                // Calculate remaining seconds directly in PHP to avoid timezone issues/computer clock skew
                remainingSeconds: {{ max(0, $studentExam->started_at->addMinutes($exam->duration_minutes)->timestamp - now()->timestamp) }},
                qIdToIndex: {
                    @foreach($questions as $index => $q)
                    {{ $q->id }}: {{ $index }},
                    @endforeach
                },
                
                init() {
                    // Load answers
                    @foreach($studentExam->answers as $ans)
                        this.answers[{{ $ans->question_id }}] = {{ $ans->option_id }};
                        // Mark answered as visited
                        if (this.qIdToIndex[{{ $ans->question_id }}] !== undefined) {
                            this.visited.add(this.qIdToIndex[{{ $ans->question_id }}]);
                        }
                    @endforeach
                    
                    this.startTimer();
                },

                startTimer() {
                    // Update the display immediately
                    this.updateTimerDisplay();

                    const timerId = setInterval(() => {
                        this.remainingSeconds--;
                        
                        if (this.remainingSeconds <= 0) {
                            clearInterval(timerId);
                            document.getElementById('timer').innerText = "00:00:00";
                            // Only alert if we actually hit zero while the user was on the page (prevent instant loop on reload if already done)
                            // But for safety, just submit
                            console.log('Timer finished'); 
                            this.autoSubmit();
                            return;
                        }

                        this.updateTimerDisplay();
                    }, 1000);
                },

                updateTimerDisplay() {
                    const remaining = Math.max(0, this.remainingSeconds);
                    const h = Math.floor(remaining / 3600).toString().padStart(2, '0');
                    const m = Math.floor((remaining % 3600) / 60).toString().padStart(2, '0');
                    const s = (remaining % 60).toString().padStart(2, '0');
                    
                    const timerEl = document.getElementById('timer');
                    timerEl.innerText = `${h}:${m}:${s}`;
                    
                    // Warning color
                    if (remaining < 300) { // 5 mins
                        timerEl.classList.remove('text-green-400');
                        timerEl.classList.add('text-red-500', 'animate-pulse');
                    }
                },

                nextQuestion() {
                    if (this.currentQuestion < this.questionsCount - 1) {
                        this.currentQuestion++;
                        this.visited.add(this.currentQuestion);
                    }
                },

                prevQuestion() {
                    if (this.currentQuestion > 0) {
                        this.currentQuestion--;
                        this.visited.add(this.currentQuestion);
                    }
                },
                
                jumpToQuestion(index) {
                    this.currentQuestion = index;
                    this.visited.add(index);
                },

                submitAnswer(qId, oId) {
                    this.answers[qId] = oId;
                    this.visited.add(this.currentQuestion);
                    
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

                toggleMark() {
                    if (this.marked.has(this.currentQuestion)) {
                        this.marked.delete(this.currentQuestion);
                    } else {
                        this.marked.add(this.currentQuestion);
                    }
                },
                
                isMarked(index) {
                    return this.marked.has(index);
                },

                getPaletteClass(index, qId) {
                    // Priority: Current > Marked > Answered > Visited > Default
                    const isCurrent = this.currentQuestion === index;
                    const isMarked = this.marked.has(index);
                    const isAnswered = this.answers[qId] !== undefined;
                    const isVisited = this.visited.has(index);

                    if (isCurrent) return 'current';
                    if (isMarked) return 'marked';
                    if (isAnswered) return 'answered';
                    if (isVisited) return 'visited'; // Visited but not answered (Gray)
                    
                    return ''; // Default (White/Light Gray)
                },
                
                get answeredCount() { return Object.keys(this.answers).length; },
                get markedCount() { return this.marked.size; },
                get notVisitedCount() { return this.questionsCount - this.visited.size; },
                get notAnsweredCount() { return Math.max(0, this.visited.size - this.answeredCount); },

                confirmSubmit() {
                    if (confirm("Are you sure you want to finish the exam? This cannot be undone.")) {
                        document.getElementById('submit-exam-form').submit();
                    }
                },

                autoSubmit() {
                    // Optional: Check if already submitted text is visible to avoid double-submit
                    if (document.getElementById('submit-exam-form')) {
                        alert("Time is up! Submitting exam...");
                        document.getElementById('submit-exam-form').submit();
                    }
                }
            }));
        });
    </script>
</body>
</html>
