<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <a href="{{ route('admin.reports.index') }}" class="text-sm font-medium text-indigo-500 hover:text-indigo-600 mb-2 block">&lt;- Back to Reports</a>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Results: {{ $exam->title }}</h1>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.reports.exam.excel', $exam) }}" class="btn bg-green-500 hover:bg-green-600 text-white">Export Excel</a>
                <a href="{{ route('admin.reports.exam.pdf', $exam) }}" class="btn bg-red-500 hover:bg-red-600 text-white">Export PDF</a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-8">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Student</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Score</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Percentage</div></th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"><div class="font-semibold text-left">Submitted At</div></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($results as $result)
                            <tr>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">{{ $result->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $result->user->email }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-bold text-indigo-500">{{ $result->score }} / {{ $exam->total_marks }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    @php $p = round(($result->score / $exam->total_marks) * 100, 2); @endphp
                                    <div class="font-medium @if($p >= $exam->passing_marks) text-green-500 @else text-red-500 @endif">{{ $p }}%</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    {{ $result->completed_at->format('d M, H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
