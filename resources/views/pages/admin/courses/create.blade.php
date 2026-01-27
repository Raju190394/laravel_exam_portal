<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="mb-8 font-bold text-2xl md:text-3xl text-gray-800 dark:text-gray-100">Create Course</div>
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6">
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1" for="name">Course Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" class="form-input w-full" type="text" value="{{ old('name') }}" required />
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="code">Course Code <span class="text-red-500">*</span></label>
                        <input id="code" name="code" class="form-input w-full" type="text" value="{{ old('code') }}" required />
                        @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1" for="description">Description</label>
                        <textarea id="description" name="description" class="form-textarea w-full" rows="4">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex items-center">
                        <div class="form-switch">
                            <input type="checkbox" id="is_active" name="is_active" class="sr-only" value="1" checked />
                            <label class="bg-gray-400 dark:bg-gray-700" for="is_active">
                                <span class="bg-white shadow-sm" aria-hidden="true"></span>
                                <span class="sr-only">Is Active</span>
                            </label>
                        </div>
                        <div class="text-sm text-gray-400 dark:text-gray-500 italic ml-2">Active Status</div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.courses.index') }}" class="btn border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">Cancel</a>
                    <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">Create Course</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
