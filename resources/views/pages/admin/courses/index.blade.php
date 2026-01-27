<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Courses</h1>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.courses.create') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="ml-2">Add Course</span>
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-8">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Course Name</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Code</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Students</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Exams</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Status</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Actions</div></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($courses as $course)
                            <tr>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap font-medium text-gray-800 dark:text-gray-100">{{ $course->name }}</td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">{{ $course->code }}</td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">{{ $course->students_count }}</td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">{{ $course->exams_count }}</td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="inline-flex font-medium bg-{{ $course->is_active ? 'green' : 'red' }}-100 dark:bg-{{ $course->is_active ? 'green' : 'red' }}-500/30 text-{{ $course->is_active ? 'green' : 'red' }}-600 dark:text-{{ $course->is_active ? 'green' : 'red' }}-400 rounded-full px-2.5 py-0.5">
                                        {{ $course->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                                            <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                <path d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l10-10L22.6 12l-10 10z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Note: Course will only be deleted if it has no exams.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600">
                                                <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                    <path d="M13 15h2v6h-2zM17 15h2v6h-2z" />
                                                    <path d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-8">
            {{ $courses->links() }}
        </div>

    </div>
</x-app-layout>
