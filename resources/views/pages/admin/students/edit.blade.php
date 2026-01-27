<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="mb-8 font-bold text-2xl md:text-3xl text-gray-800 dark:text-gray-100">Edit Student: {{ $student->name }}</div>
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1" for="name">Full Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" class="form-input w-full" type="text" value="{{ old('name', $student->name) }}" required />
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="email">Email Address <span class="text-red-500">*</span></label>
                        <input id="email" name="email" class="form-input w-full" type="email" value="{{ old('email', $student->email) }}" required />
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="password">Password <span class="text-gray-400 font-normal">(Leave blank to keep current)</span></label>
                        <input id="password" name="password" class="form-input w-full" type="password" />
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" class="form-input w-full" type="password" />
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Enroll In Courses <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @php $studentCourseIds = $student->courses->pluck('id')->toArray(); @endphp
                            @foreach($courses as $course)
                            <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <input type="checkbox" id="course_{{ $course->id }}" name="courses[]" value="{{ $course->id }}" class="form-checkbox" {{ (is_array(old('courses', $studentCourseIds)) && in_array($course->id, old('courses', $studentCourseIds))) ? 'checked' : '' }}>
                                <label for="course_{{ $course->id }}" class="ml-2 text-sm">
                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $course->name }}</span>
                                    <div class="text-xs text-gray-400">{{ $course->code }}</div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('courses')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.students.index') }}" class="btn border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">Cancel</a>
                    <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
