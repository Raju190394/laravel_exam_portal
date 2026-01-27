<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">{{ $exam->title }}</h1>
                <p class="text-sm text-gray-500">{{ $exam->duration_minutes }} mins | {{ $exam->questions_count }} questions</p>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Bulk Import Questions</p>
                    <a href="/comprehensive_sample_exam.docx" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium underline">
                        Download Comprehensive Sample Template (.docx with Images)
                    </a>
                </div>
                <form action="{{ route('admin.exams.import', $exam) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                    @csrf
                    <input type="file" name="file" class="text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50 shadow-xs" accept=".docx" required />
                    <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white shadow-sm py-1.5 px-3 text-xs flex items-center">
                        <svg class="w-4 h-4 fill-current shrink-0 mr-2" viewBox="0 0 16 16">
                            <path d="M11 0c1.3 0 2.6.5 3.5 1.5 1 .9 1.5 2.2 1.5 3.5 0 1.3-.5 2.6-1.5 3.5-.9 1-2.2 1.5-3.5 1.5H9v4H7V9.9L5.3 11.6 3.9 10.2 8.1 6H9c.8 0 1.5-.3 2.1-.9.6-.6.9-1.3.9-2.1s-.3-1.5-.9-2.1c-.6-.6-1.3-.9-2.1-.9s-1.5.3-2.1.9c-.6.6-.9 1.3-.9 2.1v1H4v-1c0-1.3.5-2.6 1.5-3.5C6.4.5 7.7 0 9 0h2Z" />
                        </svg>
                        Import Now
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <!-- Questions List -->
        <div class="space-y-6">
            @forelse($exam->questions as $index => $question)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-lg">Q{{ $index + 1 }}: {{ $question->question_text }}</h3>
                    <span class="text-sm font-medium bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $question->marks }} Marks</span>
                </div>
                
                @if($question->question_image)
                <div class="mb-4">
                    <img src="{{ Storage::url($question->question_image) }}" alt="Question Image" class="max-w-md rounded shadow-sm" />
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($question->options as $option)
                    <div class="p-4 rounded-lg border {{ $option->is_correct ? 'border-green-500 bg-green-50' : 'border-gray-200 dark:border-gray-700' }}">
                        <div class="flex items-center">
                            <span class="w-6 h-6 flex items-center justify-center rounded-full mr-3 {{ $option->is_correct ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}">
                                {{ chr(65 + $loop->index) }}
                            </span>
                            <span class="{{ $option->is_correct ? 'font-bold text-green-700' : '' }}">{{ $option->option_text }}</span>
                        </div>
                        @if($option->option_image)
                        <div class="mt-2">
                            <img src="{{ Storage::url($option->option_image) }}" alt="Option Image" class="max-w-xs rounded" />
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-12 text-center text-gray-500">
                No questions found. Import from Word file to get started.
            </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
