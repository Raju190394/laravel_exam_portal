<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Exams</h1>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <a href="{{ route('admin.exams.create') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="ml-2">Create Exam</span>
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-8">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Exam Details</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Course</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Questions</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Duration</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Activation</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Actions</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($exams as $exam)
                            <tr>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">{{ $exam->title }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-left">
                                        <span class="font-medium text-indigo-500">{{ $exam->course->name }}</span>
                                        <div class="text-xs text-gray-400">{{ $exam->course->code }}</div>
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-left font-medium text-green-500">{{ $exam->questions_count }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-left">{{ $exam->duration_minutes }} mins</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-xs space-y-1">
                                        @if($exam->active_from)
                                            <div><span class="text-gray-400">From:</span> {{ \Carbon\Carbon::parse($exam->active_from)->format('d M, H:i') }}</div>
                                        @else
                                            <div class="text-green-500 font-medium">Always Active</div>
                                        @endif
                                        @if($exam->active_until)
                                            <div><span class="text-gray-400">Until:</span> {{ \Carbon\Carbon::parse($exam->active_until)->format('d M, H:i') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <div class="space-x-1">
                                        <a href="{{ route('admin.exams.show', $exam) }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 rounded-full">
                                            <span class="sr-only">View</span>
                                            <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                <path d="M16 20c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                                <path d="M16 9c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600 rounded-full" onclick="return confirm('Are you sure?')">
                                                <span class="sr-only">Delete</span>
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

        <!-- Pagination -->
        <div class="mt-8">
            {{ $exams->links() }}
        </div>

    </div>
</x-app-layout>
