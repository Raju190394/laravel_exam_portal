<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Create Exam</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-8">
            <div class="p-6">
                <form action="{{ route('admin.exams.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="title">Title <span class="text-red-500">*</span></label>
                            <input id="title" name="title" class="form-input w-full" type="text" required />
                        </div>

                        <!-- Course -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="course_id">Course <span class="text-red-500">*</span></label>
                            <select id="course_id" name="course_id" class="form-select w-full" required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="duration_minutes">Duration (Minutes) <span class="text-red-500">*</span></label>
                            <input id="duration_minutes" name="duration_minutes" class="form-input w-full" type="number" value="60" required />
                        </div>

                        <!-- Activate From -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="active_from">Activate From</label>
                            <input id="active_from" name="active_from" class="form-input w-full cursor-pointer bg-white dark:bg-gray-800" type="datetime-local" />
                            <p class="text-xs text-gray-500 mt-1 italic">Leave empty to activate immediately</p>
                        </div>

                        <!-- Activate Until -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="active_until">Activate Until</label>
                            <input id="active_until" name="active_until" class="form-input w-full cursor-pointer bg-white dark:bg-gray-800" type="datetime-local" />
                            <p class="text-xs text-gray-500 mt-1 italic">Leave empty for no deadline</p>
                        </div>

                        <!-- Passing Marks -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="passing_marks">Passing Marks <span class="text-red-500">*</span></label>
                            <input id="passing_marks" name="passing_marks" class="form-input w-full" type="number" step="0.01" value="33" required />
                        </div>

                        <!-- Negative Marking -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="negative_marking_value">Negative Marks (per wrong answer)</label>
                            <input id="negative_marking_value" name="negative_marking_value" class="form-input w-full" type="number" step="0.01" value="0.25" />
                        </div>

                        <!-- Randomize Questions -->
                        <div class="flex items-center">
                            <div class="form-switch">
                                <input type="checkbox" id="randomize_questions" name="randomize_questions" class="sr-only" value="1" />
                                <label class="bg-gray-400 dark:bg-gray-700" for="randomize_questions">
                                    <span class="bg-white shadow-sm" aria-hidden="true"></span>
                                    <span class="sr-only">Randomize Questions</span>
                                </label>
                            </div>
                            <div class="text-sm text-gray-400 dark:text-gray-500 italic ml-2">Randomize Questions</div>
                        </div>

                        <!-- Randomize Options -->
                        <div class="flex items-center">
                            <div class="form-switch">
                                <input type="checkbox" id="randomize_options" name="randomize_options" class="sr-only" value="1" />
                                <label class="bg-gray-400 dark:bg-gray-700" for="randomize_options">
                                    <span class="bg-white shadow-sm" aria-hidden="true"></span>
                                    <span class="sr-only">Randomize Options</span>
                                </label>
                            </div>
                            <div class="text-sm text-gray-400 dark:text-gray-500 italic ml-2">Randomize Options</div>
                        </div>

                        <!-- One Question At A Time -->
                        <div class="flex items-center">
                            <div class="form-switch">
                                <input type="checkbox" id="one_question_at_a_time" name="one_question_at_a_time" class="sr-only" value="1" />
                                <label class="bg-gray-400 dark:bg-gray-700" for="one_question_at_a_time">
                                    <span class="bg-white shadow-sm" aria-hidden="true"></span>
                                    <span class="sr-only">One Question At A Time</span>
                                </label>
                            </div>
                            <div class="text-sm text-gray-400 dark:text-gray-500 italic ml-2">One Question At A Time</div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">Create Exam</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
