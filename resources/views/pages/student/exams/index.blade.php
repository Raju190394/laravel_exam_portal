<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Welcome Banner -->
        <div class="relative bg-indigo-200 dark:bg-indigo-500 p-4 sm:p-6 rounded-3xl overflow-hidden mb-8">
            <div class="relative">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold mb-1">Available Exams</h1>
                <p class="dark:text-indigo-200">Test your knowledge and prepare for your competition.</p>
            </div>
        </div>

        <!-- Available Exams Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($exams as $exam)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded">{{ $exam->duration_minutes }} Mins</span>
                        <div class="flex -space-x-2">
                             <span class="text-xs text-gray-400">{{ $exam->questions_count }} Que</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">{{ $exam->title }}</h3>
                    <p class="text-sm text-gray-500 mb-6 line-clamp-2">{{ $exam->description ?? 'No description available for this examination.' }}</p>
                    
                    @php
                        $hasAttempted = $attempts->where('exam_id', $exam->id)->where('status', 'completed')->first();
                    @endphp

                    @if($hasAttempted)
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-green-600">Score: {{ $hasAttempted->score }}</span>
                            <a href="{{ route('student.results.show', $hasAttempted) }}" class="text-indigo-500 hover:text-indigo-600 font-bold text-sm">View Result</a>
                        </div>
                    @else
                        <a href="{{ route('student.exams.take', $exam) }}" class="btn w-full bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl">Start Exam</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($attempts->isNotEmpty())
        <!-- Recent Brain History -->
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Your Recent Attempts</h2>
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-2 py-3 text-left">Exam</th>
                            <th class="px-2 py-3 text-left">Date</th>
                            <th class="px-2 py-3 text-left">Score</th>
                            <th class="px-2 py-3 text-left">Status</th>
                            <th class="px-2 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($attempts as $attempt)
                        <tr>
                            <td class="px-2 py-4 font-medium">{{ $attempt->exam->title }}</td>
                            <td class="px-2 py-4">{{ $attempt->created_at->format('M d, Y') }}</td>
                            <td class="px-2 py-4 font-bold text-indigo-500">{{ $attempt->score }}</td>
                            <td class="px-2 py-4 capitalize">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $attempt->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $attempt->status }}
                                </span>
                            </td>
                            <td class="px-2 py-4 text-right">
                                <a href="{{ route('student.results.show', $attempt) }}" class="text-indigo-500 hover:underline">Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
